@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
@endpush

@push('scripts')
    <script type="module" src={{ url('js/infinite-scroll.js') }}  defer></script>
    <script type="module" src="{{ asset('js/tasks.js') }}" defer></script>
@endpush

@section('content')

    <section class="tasks-content">
        
        <section class="tasks-list">
            <header>
                <section class="search">

                    <form method="GET" id="search" action="{{route('show_tasks',['project'=>$project])}}">
                        <input type="search" name="query" placeholder="&#128269 Search" aria-label="Search"
                               id="search-bar" value="{{$query}}"/>
                        <button class="" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                        <div class="filters">
                        <select  name="status">
                            @if($status==='')<option selected value="">Filters</option>
                            @else <option value="">Filter</option>
                            @endif
                            @if($status==='open')<option selected value="open">open</option>
                            @else <option value="open">open</option>
                            @endif
                            @if($status==='closed')<option selected value="closed">closed</option>
                            @else <option value="closed">closed</option>
                            @endif
                            @if($status==='$canceled')<option selected value="canceled">canceled</option>
                            @else <option value="canceled" >canceled</option>
                            @endif
                        </select>
                        </div>

                    </form>
                </section>
                <section>
                    <span>
                        <span class="status open">
                            <i class="fa-solid fa-folder-open"></i>
                            {{$open}} Open
                        </span>
                    </span>
                    <span>
                        <span class="status closed">
                            <i class="fa-solid fa-folder-closed"></i>
                            {{$closed}} Closed
                        </span>
                    </span>
                    <span>
                        <span class="status cancelled">
                            <i class="fa-solid fa-ban"></i>
                            {{$canceled}} Cancelled
                        </span>
                    </span>
                    <a class="button" href="{{ route('createTask', ['project' => $project]) }}"> + Add a Task </a>
                </section>
            </header>
            <section class="tasks" data-project="{{$project->id}}">
                @foreach($tasks as $task)
                    @include('partials.taskCard',['task'=>$task])

                @endforeach
            </section>

        </section>
    </section>

@endsection