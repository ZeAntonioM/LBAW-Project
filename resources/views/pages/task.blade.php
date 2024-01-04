@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@push('scripts')
    <script type="module" src={{ url('js/comments.js') }}  defer></script>
    <script type="module" src="{{ asset('js/modal.js') }}" defer></script>
    <script type="module" src="{{ asset('js/editComment.js') }}" defer></script>
    <script type="module" src="{{ asset('js/task.js') }}" defer></script>
@endpush

@section('content')
    <section class="taskPage">
        @include('partials.modal', [
            'modalTitle' => 'Close Task',
            'modalBody' => 'Are you sure that you want to mark this task as closed?',
            'actionId' => 'closeTaskBtn',
            'openDialogClass' => 'openCloseModal',
        ])
        @include('partials.modal', [
            'modalTitle' => 'Cancel Task',
            'modalBody' => 'Are you sure that you want to mark this task as canceled?',
            'actionId' => 'cancelTaskBtn',
            'openDialogClass' => 'openCancelModal',
        ])
        @include('partials.modal', [
            'modalTitle' => 'Reopen Task',
            'modalBody' => 'Are you sure that you want to mark this task as open?',
            'actionId' => 'reopenTaskBtn',
            'openDialogClass' => 'openReopenModal',
        ])
        <header>
            <section class="info">
                <h1 class="title">{{$task->title}}</h1>

                @if($task->status == 'open') <span class="status open"> <i class="fa-solid fa-folder-open"></i> Open </span>
                @elseif($task->status == 'closed') <span class="status closed"> <i class="fa-solid fa-folder-closed"></i> Closed </span>
                @else <span class="status canceled"> <i class="fa-solid fa-ban"></i> Canceled </span>
                @endif
            </section>
            <section class="actions">
                @can('update', $task)
                    <a class="edit buttonLink" href="{{route('edit_task',['project'=>$project,'task'=>$task])}}"> <i class="fa-solid fa-pen-to-square"></i> Edit</a>
                    <a class="cancel buttonLink openCancelModal"> <i class="fa-solid fa-ban"></i> Cancel</a>
                @endcan
            </section>
        </header>
        <section class="container">
            <section class="primaryContainer">

                <section class="description">
                    {{$task->description}}
                </section>

                @can('changeStatus', $task)
                    @if ($task->status != 'open')
                        <a class="buttonLink openReopenModal"> <i class="fa-solid fa-folder-open"></i> Reopen task </a>
                    @else
                        <a class="buttonLink openCloseModal"> <i class="fa-solid fa-folder-closed"></i> Close task </a>
                    @endif
                @endcan
            <div>
            <div class ="container-fluid  p-2">
                <div class = "row p-3 containerBoot comments" data-project="{{$project->id}}" data-task ="{{$task->id}}">
            @forelse($comments as $comment)
                @include('partials.commentCard',['project'=>$project,'task'=>$task,'comment'=>$comment])
                @empty
                <span> No comments for this task.</span>
            @endforelse
            </div>
            </div>
            </div>
            <div class="new-comment">
            <form action= "{{ route('store_comment', ['project' => $project, 'task' => $task->id]) }}" method="POST">
                @csrf
                <div class="new-comment-body">
                    <textarea name="content" id="content" cols="30" rows="10" placeholder="Write your comment here..."></textarea>
                </div>
               
                <div class="new-comment-footer">
                    <button type="submit">Comment</button>
                </div>
            </form>
        </div>
            </section>
            <section class="sideContainer">
                <section class="deadlineContainer" >
                    <span> <i class="fa-solid fa-clock"></i>
                        @if ($task->endtime == null)
                            Deadline:
                            @if ($task->deadline) {{ date('d-m-Y', strtotime($task->deadline)) }}
                            @else There is no deadline
                            @endif
                        @else
                            {{ ucwords($task->status) }} at:  {{ date('d-m-Y', strtotime($task->endtime)) }}
                        @endif
                    </span>
                </section>
                @if ($task->status != 'open')
                    <section id="finishedTaskUser">
                        <span><i class="fa-solid fa-user"></i> {{ ucwords($task->status) }} by: {{$task->closed_by->name}}</span>
                    </section>
                @endif
                <section class="assignContainer">
                    <h5><i class="fa-solid fa-users"></i> Assigned: </h5>
                    <ul class="assign">
                        @foreach($assign as $user)
                            <li>{{$user->name}}</li>
                        @endforeach
                    </ul>
                </section>
                <section class="tagsContainer">
                    <h5><i class="fa-solid fa-tag"></i> Tags: </h5>
                    <ul class="tags">
                        @foreach($tags as $tag)
                            <li>{{$tag->title}}</li>
                        @endforeach
                    </ul>
                </section>
                <section class="taskCreator">
                    <span><i class="fa-solid fa-user"></i> Creator: {{$creator->name}}</span>
                </section>
            </section>
        </section>
    </section>
@endsection