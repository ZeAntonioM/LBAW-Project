@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">

@endpush

@section('content')
    <section class="projectCreation">
        <section class="formContainer">
            <header class="tasks">
                <h2>Edit your <span class="shine">Project</span></h2>
            </header>
            <form action="{{ route('action_edit_project', ['project' => $project]) }}" method="POST">
                @csrf
                @method('PUT')
                <section class="primaryContainer">
                    @if (old('title') == null && !$errors->has('title'))
                        <input type="text" name="title" placeholder="Project's Title" value="{{ $project->title }}" required>
                    @else
                        <input type="text" name="title" placeholder="Project's Title" value="{{ old('title') }}" required>
                    @endif

                    @if ($errors->has('title'))
                        <span class="error">
                            {{ $errors->first('title') }}
                        </span>
                    @endif

                    @if (old('description') == null && !$errors->has('description'))
                        <textarea name="description" placeholder="Project's Description">{{ $project->description }}</textarea>
                    @else
                        <textarea name="description" placeholder="Project's Description">{{ old('description') }}</textarea>
                    @endif

                    @if ($errors->has('description'))
                        <span class="error">
                            {{ $errors->first('description') }}
                        </span>
                    @endif

                    <label for="deadline">
                        <i class="fa-regular fa-calendar"></i> Deadline
                    </label>
                    @if (old('deadline') == null && !$errors->has('deadline'))
                        <input type="date" name="deadline" id="deadline" value = "{{ date('Y-m-d', strtotime($project->deadline)) }}">
                    @else
                        <input type="date" name="deadline" id="deadline" value = "{{ old('deadline') }}">
                    @endif

                    @if ($errors->has('deadline'))
                        <span class="error">
                            {{ $errors->first('deadline') }}
                        </span>
                    @endif

                    <section class="buttons">
                        <button type="submit">
                            Submit
                        </button>
                        <a href="{{ URL::previous() }}">
                            Cancel
                        </a>
                    </section>
                </section>
            </form>
        </section>
    </section>

@endsection
