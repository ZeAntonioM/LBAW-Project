<?php

namespace App\Http\Controllers;

use App\Events\CommentNotificationEvent;
use App\Events\PostNotificationEvent;
use App\Events\ProjectNotificationEvent;
use App\Events\TaskNotificationEvent;
use App\Models\Project;
use App\Models\Task;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{

    private $possibleStatus = ['open', 'closed', 'canceled'];

    public function searchTasks(Request $request, Project $project)
    {
        if ($project == null)
            return response()->json(['error' => 'Project with specified id not found'], 404);

        // $this->authorize('create', [Task::class, $project]);
        $searchedTasks = $project->tasks()
            ->with('created_by')
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$request->input('query')])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$request->input('query')])
        ;
        if($request->input('status') and in_array($request->input('status'),$this->possibleStatus)) $searchedTasks = $searchedTasks->where('status','=',$request->input('status'));

        if ($request->ajax())
            return response()->json($searchedTasks->get());
        else {
            return $searchedTasks->paginate(10)->withQueryString();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @throws AuthorizationException
     */
    public function create(Request $request, Project $project)
    {
        $this->authorize('create', [Task::class,  $project]);
        return view('pages.' . 'createTask')->with(['project'=>$project, 'users'=>$project->users,'tags'=>$project->tags,'task'=>null]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(Request $request, Project $project)
    {
        // Validate input
        $this->authorize('create', [Task::class, $project]);
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:today',
            'users' => 'nullable',
            'tags' => 'nullable'
        ]);
        // Add Policy thing


        $task = new Task();
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->opened_user_id= Auth::user()->id;
        $task->deadline = $validated['deadline'];
        $task->project_id = $project->id;

        $users = array_map('intval', explode(',', $validated['users']));
        $tags =array_map('intval', explode(',', $validated['tags']));
        $task->save();
        if($validated['users'])foreach ($users as $user) $task->assigned()->attach($user);
        if($validated['tags'])foreach ($tags as $tag) $task->tags()->attach($tag);

        return redirect()->route('task',['project'=>$project,'task'=>$task]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task)
    {
        $project_task = $task->project;

        if ($project_task->id != $project->id || $task == null || $project_task == null) 
            return abort(404);

        $this->authorize('view', [$task::class, $task]);
        $users = $task->assigned;

        $tags = $task->tags;
        
        $creator = $task->created_by;

        $comments = $task->comments()->paginate(5);
 
        return view('pages.task',['project' => $project_task, 'task'=>$task, 'assign'=>$users,'tags'=>$tags, 'comments' => $comments,'creator'=>$creator]);
    }

    public function getNextItems(Request $request)
    {   
        $page = $request->input('page', 1);
        $taskId = $request->input('task');
        $projectId =$request->input('project');
        $project =Project::find($projectId);
        $task = Task::find($taskId);
        $comments = $task->comments()->paginate(5, ['*'], 'page', $page);
        $htmlArray = [];
        for ($i = 0; $i < $comments->count(); $i++) {
            $htmlArray[] = view('partials.commentCard', ['comment' => $comments[$i], 'task' => $task,'project'=>$project])->render();
        }
        return response()->json(['htmlArray' => $htmlArray]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task)
    {
        return view('pages.createTask',['project'=>$project, 'users'=>$project->users,'tags'=>$project->tags,'task'=>$task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Validate input
        $this->authorize('update', [Task::class, $task]);
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:today',
            'users' => 'nullable',
            'tags' => 'nullable'
        ]);
        // Add Policy thing

        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->deadline = $validated['deadline'];
        $users = array_map('intval', explode(',', $validated['users']));
        $tags =array_map('intval', explode(',', $validated['tags']));
        $task->save();
        $task->assigned()->detach();
        if($validated['users'])foreach ($users as $user) $task->assigned()->attach($user);
        $task->tags()->detach();
        if($validated['tags'])foreach ($tags as $tag) $task->tags()->attach($tag);

        return redirect()->route('task',['project'=>$task->project,'task'=>$task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    public function editStatus(Request $request, Project $project, Task $task) {
        $this->authorize('changeStatus', [Task::class, $task]);
        if(!$project) abort(404);
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in($this->possibleStatus),
            ],
        ]);

        $invalidStatusChange =  (($validated['status'] == 'closed' || $validated['status'] == 'canceled') && $task->status != 'open');

        $errorMsg = 'A ' . $task->status . ' task cannot be changed to another state other than open';

        if ($invalidStatusChange) {
            return response()->json(['error' => $errorMsg], 400);
        }

        $task->status = $validated['status'];
        $task->closed_user_id = $validated['status'] == 'open' ? null : Auth::id();
        $task->endtime = $validated['status'] == 'open' ? null : now();
        $task->save();
        event(new TaskNotificationEvent($project,$task, 'Task Status was changed to'.$task->status));
        return response()->json(['task' => $task, 'closed_user_name' => $task->closed_by->name]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeComment(Request $request ,$project_id,$task_id)
    {   
        $task = Task::find($task_id);
        $project = Project::find($project_id);
      
        $this->authorize('comment', [Task::class, $task]);

        $request->validate([
            'content' => 'required|string|max:1024',
        ]);

        $comment = new Comment();
        $comment->content = $request['content'];
        $comment->submit_date = date('Y-m-d');
        $comment->last_edited = null;
        $comment->user_id = Auth::user()->id;
        $comment->task_id = $task_id;
        $comment->save();
        event(new CommentNotificationEvent($project,$task, $request['content']));
        return redirect()->back();

    }

    public function deleteComment(Project $project, Task $task, $comment_id)
    {  
        $comment = Comment::find($comment_id);
        $this->authorize('delete_comment', [Task::class,$comment]);
        $comment->delete();
        return redirect()->route('task', ['project'=>$project,'task'=>$task]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function editComment(Request $request,$project,$task,$comment_id)
    {
        $comment = Comment::find($comment_id);
        $this->authorize('edit_comment', [Task::class,$comment]);
        $comment->content = $request['content'];
        $comment->save();
        return true;
    }

    /**
     * Update the specified resource in storage.
     */
}
