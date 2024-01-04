@extends('layouts.app')

@section('navbar')
    <li id="projectHome"><a href="{{route('project',['project'=>$project->id])}}"> <i class="fa-solid fa-house"></i> Project Home</a></li>
    <li id="projectTeam"><a href="{{route('team',['project'=>$project->id])}}"> <i class="fa-solid fa-users"></i> Team</a></li>
    <li id="projectTasks"><a href="{{route('show_tasks',['project'=>$project->id])}}"> <i class="fa-solid fa-list-check"></i> Tasks</a></li>
    <li id="projectTags"><a href="{{route('project_tags',['project'=>$project->id])}}"> <i class="fa-solid fa-tags"></i> Tags</a></li>
    <li id="projectFiles"><a href = "{{route('show_project_files',['project' => $project->id])}}"> <i class="bi bi-folder-fill"></i> Files </a> </li>
    <li id="projectForum"><a href="{{route('forum',['project'=>$project->id])}}"> <i class="fa-solid fa-comments"></i> Forum</a></li>
@endsection
