@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
@endpush

@push('scripts')
    <script type="module" src={{ url('js/app.js') }} defer></script>
    <script type="module" src={{ url('js/task.js') }} ></script>
@endpush

@section('content')
    {{ old('deadline') }}
    <section class="taskCreation">

        <section class="formContainer">
            <header class="tasks">
                @if($task)
                    <h2>Edit the <span class="shine">Task</span></h2>
                @else
                    <h2>Create a <span class="shine">Task</span></h2>
                @endif
            </header>
            @if(!$task) <form method="POST" action="{{ route('newTask', ['project' => $project]) }}" id="create_task">
                @else <form method="POST" action="{{ route('update_task', ['project' => $project,'task'=>$task?:-1]) }}" id="create_task">
                    @method('PUT')
                    @endif
                    @csrf
                    <section class="primaryContainer">
                        @if(old('title'))
                            <input type="text" name="title" placeholder="Task Title" required
                                   value="{{ old('title') }}">
                        @else
                            <input type="text" name="title" placeholder="Task Title" required
                                   value="{{ $task? $task->title:'' }}">
                        @endif
                        @if ($errors->has('title'))
                            <span class="error">
                            {{ $errors->first('title') }}
                        </span>
                        @endif

                        @if (old('description') !== null)
                            <textarea name="description"
                                      placeholder="Project's Description">{{ old('description') }}</textarea>
                        @else
                            <textarea name="description"
                                      placeholder="Project's Description">{{$task? $task->description:''}}</textarea>
                        @endif

                        @if ($errors->has('description'))
                            <span class="error">
                            {{ $errors->first('description') }}
                        </span>
                        @endif


                    </section>
                    <section class="sideContainer">
                        <label for="deadline">
                            <i class="fa-solid fa-calender"></i> Deadline
                        </label>
                        @if(old('deadline'))
                            <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}">
                        @else

                            <input id="deadline" type="date" name="deadline"
                                   value="{{ $task?date('Y-m-d', strtotime($task->deadline)):null}}">
                        @endif
                        @if ($errors->has('deadline'))
                            <span class="error">
                            {{ $errors->first('deadline') }}
                        </span>
                        @endif

                        @if(old('users'))
                            @include('partials.multiselector',["data"=>"users","selected"=>[]])
                        @else
                            @include('partials.multiselector',["data"=>"users","selected"=>($task?$task->assigned:[])])
                        @endif

                        @if(old('tags'))
                            @include('partials.multiselector',["data"=>"tags","selected"=>[]])
                        @else
                            @include('partials.multiselector',["data"=>"tags","selected"=>($task?$task->tags:[])])
                        @endif
                        @if ($errors->has('tags'))
                            <span class="error">
                            {{ $errors->first('tags') }}
                        </span>
                        @endif

                    </section>

                    <input type="hidden" name='users' id="assigns" value="">
                    <input type="hidden" name='tags' id="tags" value="">
                </form>
                <section class="buttons">
                    <button type="submit" form="create_task">
                        {{$task?'Edit':'Create'}}
                    </button>
                    <a href="{{$task?route('task',['project'=>$project,'task'=>$task]):route('show_tasks',['project'=>$project])}}">
                        Cancel
                    </a>
                </section>

        </section>

    </section>

@endsection