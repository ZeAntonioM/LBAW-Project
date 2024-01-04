<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Project $project)
    {
        $this->authorize('create',[Tag::class,$project]);
        $validated = $request->validate([
            'title' => 'required|string|min:1|max:20|',
        ]);
        $tag = Tag::query()
            ->where('project_id','=',$project->id)
            ->where('title','=',$validated['title'])
            ->first();
        
        if($tag != null) return back()->withErrors(['title' => 'Tag already exists']);

        $tag = new Tag();
        $tag->title = $validated['title'];
        $tag->project_id = $project->id;
        $tag->save();


        return redirect()->route('project_tags',['project'=>$project] );
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $project = $tag->project;
        $this->authorize('update',[Tag::class, $project ]);
        $validated = $request->validate([
            'title' => 'required|string|min:1|max:20|',
        ]);
        if($tag->title === $validated['title']) return response()->json(['errors' => ['Title cannot be the same']], 422);
        $tag_res = Tag::query()
            ->where('project_id','=',$project->id)
            ->where('title','=',$validated['title'])
            ->first();

        if($tag_res) return response()->json(['errors' => ['Tag already exists']], 422);
        $tag->title = $validated['title'];
        $tag->save();
        return response()->json($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete',[$tag::class, $tag->project]);
        $tag->delete();
        return response()->json();
    }
    public function search(Request $request){
        response()->json(1);
        if($request->input('project')){
            $searchTerm = '%' . $request->input('query') . '%';
            $project = Project::find($request->input('project'));
            $this->authorize('view',[Tag::class,$project]);
            $tags = $project->tags()->with('tasks')->where('title','like',$searchTerm)->get();
            return response()->json($tags);
        }
    }
}
