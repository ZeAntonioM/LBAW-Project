@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@push('scripts')
    <script type="module" src ="{{ asset('js/favorites.js') }}" defer></script>
    <script type="module" src="{{ asset('js/project.js') }}" defer></script>
    <script type="module" src="{{ asset('js/modal.js') }}" defer></script>
@endpush

@section('content')
<section class="projectPage">
    @includeWhen(Auth::id() != $project->user_id, 'partials.modal', [
        'modalTitle' => 'Leave Project',
        'modalBody' => 'Are you sure that you want to leave this project?',
        'openDialogClass' => 'openLeaveModal',
        'formId' => 'leave-project-form'
    ])
    @includeWhen(Auth::id() == $project->user_id, 'partials.modalOk', [
        'modalTitle' => 'Leave Project',
        'modalBody' => 'Before leaving the project you must assign another project member as the new
        project coordinator. You can do so in the team section.',
        'type' => 'mymodal-warning',
        'openDialogClass' => 'openLeaveModal',
    ])
    <header>
        <section class="info">
        <h1 class="title">{{$project->title}}</h1>
        @if($project->is_archived) <span class="status archive"> <i class="fa-solid fa-box-archive"></i> Archived </span>
        @else <span class="status open"> <i class="fa-solid fa-box-open"></i> Open </span>
        @endif
        @if(in_array($project->id, $favorites))
            <i class="bi bi-star-fill favorite-star" style='font-size:1.5em; color:orange;' data-project="{{$project->id}}"></i>
        @else
            <i class="bi bi-star-fill favorite-star" style='font-size:1.5em; color:grey;' data-project="{{$project->id}}"></i>
        @endif
        </section>
        <section class="actions">
            <button type="button" class="openLeaveModal"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Leave</button>
            @can('update',$project)
                <button class="project-action-button edit" id="edit-project-button"> <i class="fa-solid fa-pen-to-square"></i> Edit</button>
            @endcan
            @can('archive',$project)
                <button class="project-action-button archive" id="archive-project-button"> <i class="fa-solid fa-pen-to-square"></i> Archive</button>
            @endcan
            @can('delete', $project)
                <button class="project-action-button delete" id="delete-project-button"> <i class="fa-solid fa-trash"></i> Delete</button>
            @endcan
        </section>
        <!-- Hidden forms to actions in project page that don't use AJAX-->
        <form class="hidden" id="edit-project-form" action="{{ route('show_edit_project',['project'=>$project->id])}}" method="GET">
        </form>
        <form class="hidden" id="archive-project-form" action="{{ route('archive_project',['project'=>$project->id])}}" method="POST">
            @csrf
            @method('PUT')
        </form>

        <form class="hidden" id="delete-project-form" action="{{ '/project/' . $project->id }}" method="POST">
            @csrf
            @method('DELETE')
        </form>

        <form class="hidden" id="leave-project-form" action="{{ "/project/" . $project->id . "/team/leave"}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" value="{{ Auth::id() }}" name="user">
        </form>
    </header>
    <section class="container">
    <section class="primaryContainer">

                <section class="description">
                    {{ $project->description }}
                </section>


    </section>
    <section class="sideContainer">
        <section class="completionContainer">
            <span class="completion"><i class="fa-solid fa-list-check"></i>  Completed Tasks {{$completedTasks}}/{{$allTasks}}</span>
        </section>
        <section class="deadlineContainer" >
            <span><i class="fa-regular fa-calendar"></i> Deadline:
                @if($project->deadline) {{ date('d-m-Y', strtotime($project->deadline)) }}
                @else There is no deadline
                @endif

                    </span>
                </section>
                <section class="teamContainer">
                    <span><i class="fa-solid fa-user-tie"></i> Coordinator: {{ $project->coordinator->name }}</span>
                </section>
                <section class="teamContainer">
                    <h5><i class="fa-solid fa-users"></i> Team: </h5>
                    <ul class="team">
                        @foreach ($team as $member)
                            <li>{{ $member->name }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('team', ['project' => $project->id]) }}">See all</a>
                </section>
            </section>
        </section>
    </section>
@endsection
