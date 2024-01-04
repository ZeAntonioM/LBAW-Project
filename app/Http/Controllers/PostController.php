<?php

namespace App\Http\Controllers;


use App\Events\PostNotificationEvent;
use App\Events\ProjectNotification;
use App\Models\Project;
use http\Env\Response;
use Illuminate\Http\Request;


use App\Models\Post;

use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Project $project)
    {
        
        if ($project == null) {
            if ($request->ajax())
                return response()->json(['error' => 'Project with specified id not found'], 404);
            else
                return abort(404);
        }

        $this->authorize('view_forum', [Project::class, $project]);

        $posts = $project->posts()->paginate(5);

        return view('pages.forum', ['project'=>$project, 'posts'=>$posts]);
    }
    public function getNextItems(Request $request)
    {   
        $page = $request->input('page', 1);
        $projectId = $request->input('project');
        $project = Project::find($projectId);
        $posts = $project->posts()->paginate(5, ['*'], 'page', $page);
        $htmlArray = [];
        for ($i = 0; $i < $posts->count(); $i++) {
            $htmlArray[] = view('partials.postCard', ['post' => $posts[$i],'project'=>$project])->render();
        }
        return response()->json(['htmlArray' => $htmlArray]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        
        $this->authorize('create', [Project::class, $project]);

        $request->validate([
            'content' => 'required|string|max:1024',
        ]);

        $post = new Post();
        $post->content = $request['content'];
        $post->submit_date = date('Y-m-d');
        $post->last_edited = null;
        $post->user_id = Auth::user()->id;
        $post->project_id = $project->id;
        $post->save();
        event(new PostNotificationEvent($project, $request['content']));
        return redirect()->route('forum', ['project' => $project]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project, Post $post)
    {
        if ($post == null)
            return abort(404);

        $this->authorize('update', [Post::class, $post]);

        return view('pages.editPost', ['project' => $project, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Post $post)
    {
        $this->authorize('update', [Post::class, $post]);

        $request->validate([
            'content' => 'required|string|max:1024',
        ]);

        $post->content = $request['content'];
        $post->last_edited = date('Y-m-d');
        $post->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project, Post $post)
    {
        $this->authorize('delete', [Post::class, $post]);

        $post->delete();

        return redirect()->route('forum', ['project' => $project->id]);
    }

}
