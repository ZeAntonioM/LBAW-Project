<?php

namespace App\Http\Controllers;
use App\Events\InviteNotificationEvent;
use App\Events\ProjectNotification;
use App\Events\ProjectNotificationEvent;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    private $possibleTaskStatus = ['open', 'closed', 'canceled'];
    private $possibleProjectStatus = ['archive', 'open'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewUserProjects',Project::class);

        $projects = $this->search($request);
        $user = Auth::user();
        $favorites = $user->favoriteProjects;
        $favoritesArray = $favorites->pluck('id')->toArray();

        if ($request->session()->has('message')) {
            return view('home.home',['projects'=>$projects,'query'=>$request->input('query'),'favorites'=>$favoritesArray, 'status'=>$request->input('status')])->with('message', $request->session()->get('message'));
        }
        else
            return view('home.home',['projects'=>$projects,'query'=>$request->input('query'),'favorites'=>$favoritesArray, 'status'=>$request->input('status')]);
    }

    public function search(Request $request){
        $user = $request->user();
        if($user->is_admin){

            $projects = Project::query();
        }else{
            $projects = $user->projects();
        }
        if($request->input('query')) {
            $searchedProjects = $projects->with('coordinator')
                ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$request->input('query')])
                ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$request->input('query')]);
        }else $searchedProjects = $projects;

        if($request->input('status') and in_array($request->input('status'),$this->possibleProjectStatus)){

             if($request->input('status')==='archive') {
                 $searchedProjects = $searchedProjects->where('is_archived', '=', true);

             }
             else $searchedProjects = $searchedProjects->where('is_archived','=',false);
        }
        if ($request->ajax())
            return response()->json($searchedProjects->get());
        else

            return $searchedProjects->paginate(10)->withQueryString();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        return view('pages.newProjectForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:' . date('d-m-Y'),
        ]);

        $this->authorize('create', Project::class);

        $project = new Project();
        $project->title = $validated['title'];
        $project->description = $validated['description'];
        $project->deadline = isset($validated['deadline']) ? $validated['deadline'] : null;
        $project->user_id = Auth::user()->id;
        $project->save();

        $project->users()->attach(Auth::user()->id);

        return redirect()->route('project', ['project' => $project]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {

        if ($project == null)
            return abort(404);

        $this->authorize('view', [Project::class, $project]);
        $users = $project->users;

        $completed_tasks = $project->tasks()
            ->where('tasks.status', '=', 'closed')
            ->count();

        $open_tasks = $project->tasks()
            ->where('tasks.status', '=', 'open')
            ->count();

        $all_task = $completed_tasks + $open_tasks;
        $user = Auth::user();
        $favorites = $user->favoriteProjects;
        $favoritesArray = $favorites->pluck('id')->toArray();
        return view('pages.project', ['project' => $project,'favorites' => $favoritesArray, 'team' => $users->slice(0, 4), 'allTasks' => $all_task, 'completedTasks' => $completed_tasks]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($project == null)
            return abort(404);

        $this->authorize('update', [Project::class, $project]);

        return view('pages.editProject', ['project' => $project]);
    }
    public function show_team(Project $project)

    {
        $this->authorize('view', [Project::class, $project]);
        return view('pages.team', ['team' => $project->users, 'project' => $project]);
    }
    public function show_files(Project $project){
        $this->authorize('view',[Project::class,$project]);
        return view('pages.files',['files' => $project->files,'project' => $project]);
    }
    public function add_user(Request $request, Project $project)

    {
        $this->authorize('update', [Project::class, $project]);
        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
        if ($user->is_admin) return back()->withErrors([
            'email' => 'Admins cannot be part of a project',
        ])->onlyInput('email');
        if ($user->is_blocked) return back()->withErrors([
            'email' => 'User is blocked',
        ])->onlyInput('email');
        
        if($project->users->contains($user)) return back()->withErrors([
            'email' => 'Member already in the project',
        ])->onlyInput('email');

        $project->users()->attach($user->id);
        event(new InviteNotificationEvent($project,$user, 'You were invited to join '.$project->name));
        return redirect()->route('team', ['team' => $project->users, 'project' => $project]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:' . date('d-m-Y'),
        ]);

        $this->authorize('update', [Project::class, $project]);

        $project->title = $validated['title'];
        $project->description = $validated['description'];
        $project->deadline = isset($validated['deadline']) ? $validated['deadline'] : null;
        $project->save();

        return redirect()->route('project', ['project' => $project->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {

        if ($project == null)
            return abort(404);

        $this->authorize('delete', [Project::class, $project]);
        $files = $project->files;
        foreach ($files as $file) {
            $hashedPart = FileController::getHashedPart($file);
            $filePath = '/project/'.$project->id.'/'.$hashedPart;
            Storage::disk('proj_planner_files')->delete($filePath);
            $file->delete();
        }
        Storage::disk('proj_planner_files')->deleteDirectory('project/'.$project->id);
        $project->delete();
        
        $projects = Project::all();

        return redirect()->route('home', ['projects' => $projects, 'user' => Auth::id()]);
        // TODO: redirect to "My projects page"
        // return redirect()->route('my_projects');
    }


    public function showTasks(Request $request, Project $project)
    {
        if ($project == null) {
            if ($request->ajax())
                return response()->json(['error', 'Project with specified id not found']);
            else
                return abort(404);
        }

        $this->authorize('view', [Project::class, $project]);

        $tasks = $project->tasks()->with('created_by');
        if($request->input('status') and in_array($request->input('status'),$this->possibleTaskStatus) )
            $tasks = $tasks->where('status','=',$request->input('status'));

        $tasks = $tasks->paginate(10)->withQueryString();
        $open = $project->tasks()->where('status','=','open')->count();
        $closed = $project->tasks()->where('status','=','closed')->count();
        $canceled = $project->tasks()->where('status','=','canceled')->count();
        if($request->input('query')) $tasks = app(TaskController::class)->searchTasks($request,$project);

        if ($request->ajax())
            return response()->json($tasks);
        else
            return view('pages.tasks', ['project'=>$project, 'tasks'=>$tasks, 'open'=>$open,'closed'=>$closed,'canceled'=>$canceled, 'query'=>$request->input('query'),'status'=>$request->input('status')]);
    }
    public function getNextItems(Request $request)
    {   
        $page = $request->input('page', 1);
        $projectId = $request->input('project');
        $project = Project::find($projectId);
        $tasks = $project->tasks()->with('created_by')->paginate(10, ['*'], 'page', $page)->withQueryString();
        $htmlArray = [];
        for ($i = 0; $i < $tasks->count(); $i++) {
            $htmlArray[] = view('partials.taskCard', ['task' => $tasks[$i], 'project' => $project])->render();
        }
        return response()->json(['htmlArray' => $htmlArray]);
    }


    public function remove_user(Request $request, Project $project) {
        $removedUser = User::find($request->user);
        
        if ($removedUser == null) {
            if ($request->ajax())
                return response()->json(['error' => 'User to remove from project not found'], 404);
            else
                abort(404, 'User to remove from project not found');
        }

        $this->authorize('removeUser', [Project::class, $project, $removedUser]);

        $removedUser->assign()->detach();
        $project->users()->detach($removedUser->id);

        if (Auth::user() == $removedUser)
            return redirect()->route('home', ['projects' => $removedUser->projects,'user'=>Auth::id()]);
        else
            return response()->json(['message' => 'User has been successfully removed'], 200);
    }


    public function send_email_invite(Request $request, Project $project)
    {
        $this->authorize('send_invite', [Project::class, $project]);

        $request->validate([
            'email' => 'required|email|',
        ]);

        $lastInviteToken = DB::table('invites')
            ->where('email', $request->email)
            ->where('project_id', $project->id)
            ->orderBy('invite_date', 'desc')
            ->first();

        if ($lastInviteToken && Date::parse($lastInviteToken->invite_date)->diffInMinutes(now()) < 5) {
            return response()->json(['message' => 'Invite to ' . $request->email . ' already sent within the last 5 minutes'], 429);
        }
        
        $token = Str::random(64);
  
        DB::table('invites')->insert([
            'email' => $request->email, 
            'project_id' => $project->id,
            'token' => $token,
            'invite_date' => now(),
        ]);
    
        $mailData = [
            'email' => $request->email,
            'token' => $token,
            'subject' => "Invite to join project",
        ];
        
        MailController::send($mailData);
        
        return response()->json(['message' => 'Invite to ' . $request->email . ' sent successfully'], 200);
    }

    public function show_tags(Request $request, Project $project){
        $this->authorize('view', $request->user());
        return view('project.tags',['project'=>$project, 'tags'=>$project->tags()->with('tasks')->get()]);
    }

    public function assign_coordinator(Request $request, Project $project) {
        $this->validate($request, [
            'user_id' => [
                'required',
                'integer',
                Rule::in($project->users->pluck('id')->toArray()),
                'different:' . Auth::id(),
            ]
        ]);

        $this->authorize('assign_coordinator', [Project::class, $project]);

        $project->user_id = $request->input('user_id');
        $project->save();

        return redirect()->route('project', ['project' => $project->id]);
    }
    public function archive(Project $project){
        $this->authorize('archive',[Project::class,$project]);
        if($project->is_archived == false){
            $project->is_archived = true;
        }
        $project->save();
        return redirect()->route('project', ['project' => $project->id]);
    }



    public function favorite(Request $request,$projectId){
          
            $userId = Auth::user()->id;

         
            $exists = DB::table('favorites')
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->exists();

            if ($exists) {
                DB::table('favorites')
                    ->where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->delete();
    
                return response()->json(['status' => 'unfavorited']);
            } else {
                 DB::table('favorites')->insert([
                    'user_id' => $userId,
                    'project_id' => $projectId,
                ]);
    
                return response()->json(['status' => 'favorited']);
            }
    }

}
