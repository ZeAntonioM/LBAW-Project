@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')

<div class="container-fluid">
	<div class="row first">
	<div class="col-12 d-flex justify-content-between mb-5">
        <header>
            <h1>User Details</h1>
        </header>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Options
            </button>
            <ul class="dropdown-menu p-4">
            <li>
                <h6 class="dropdown-header">Profile Actions</h6>
            </li>
            <li>
                <a href= "{{ route('edit_profile', ['user' => Auth::user()]) }}" class="dropdown-item editbutton">Edit Account</a>
            </li>
            <li>
                <a href= "{{ route('show_delete_profile', ['user' => Auth::user()]) }}" class="dropdown-item delete-btn">Delete Account</a>
            </li>
            </ul>
        </div>
    </div>
    <div class="col-12  mb-5">
        <div class = "row">
            <div class = " col-sm-12 col-lg-2 d-flex justify-content-center ">
                <figure>
                    <img src="{{$image}}" alt="Default Image" >
                </figure>
            </div>
            <div class = "col-sm-12 col-lg-10   d-flex flex-column justify-content-center">
                <div class="row">
                    <div class="col-12">
                        <div class="row ">
                            <div class="col col-sm-4 mx-auto text-center d-flex justify-content-center justify-content-lg-start">
                                <div class = "row">
                                    <div class = "col-12">
                                        <span class="infos">
                                            <i class="bi bi-person-fill"></i> Name
                                        </span>
                                    </div>
                                    <div class = "col-12">
                                        <span class="infos">
                                            {{$user->name}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-sm-4 mx-auto text-center  d-flex justify-content-center justify-content-lg-start">
                                <div class = "row">
                                    <div class = "col-12">
                                        <span class="infos">
                                            <i class="bi bi-person-fill"></i> Email
                                        </span>
                                    </div>
                                    <div class = "col-12">
                                        <span class="infos">{{$user->email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-sm-4  mx-auto text-center d-flex  justify-content-center justify-content-lg-start">
                                <div class = "row">
                                    <div class = "col-12">
                                        <span class="infos">
                                            <i class="bi bi-person-fill"></i> Role
                                        </span>
                                    </div>
                                    <div class = "col-12">
                                        @if($user->is_admin)
                                            <span class="infos">Admin Account</span>
                                        @else <span class="infos">User Account</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class ="row second">

        <div class = "col-12 mb-5 ">
            <header><h1><i class="fa-solid fa-list-check"></i> Critical Tasks (Deadline Approaching)</h1></header>
        </div>

    <div class = "col-12 mb-5">
        <div class ="row">

   
        @php $taskCount = 0 @endphp
        @forelse($tasks as $task)
            @if($taskCount < 5)
            <div class="col-12 mb-4">
                <div class = "row p-3 task">
                    <div class ="col-sm-12 col-lg-3 d-flex justify-content-center taskinfo">
                        <span class="text-truncate">
                            {{$task['task']->title}} - {{$task['task']->description}}
                        </span>
                    </div>
                    <div class="col-sm-6 col-lg-3 d-flex justify-content-center taskinfo">
                        <span><i class="bi bi-calendar-week"></i> {{ \Carbon\Carbon::parse($task['task']->deadline)->format('Y/m/d') }}</span>
                    </div>
                    <div class ="col-sm-6 col-lg-3 d-flex justify-content-center taskinfo ">
                    @if($task['task']->status == "closed")
                        <span>Status:</span>
                        <span style="color: #ff3333;">{{ $task['task']->status }}</span>
                    @elseif($task['task']->status == "canceled")
                        <span>Status: </span>
                        <span style="color: orange;">{{ $task['task']->status }}</span>
                    @elseif($task['task']->status == "open")
                        <span>Status: </span>
                        <span style="color: #2E9D7F;"> {{ $task['task']->status }}</span>
                    @else
                        <span>{{ $task['task']->status }}</span>
                    @endif
                    </div>
                    <div class="col-sm-12 col-lg-3 d-flex justify-content-center ">
                        <a href="{{ route('task', ['project' => $task['project'], 'task' => $task['task']]) }}" class = "btn taskbtn" >Go to Task</a>
                    </div>
                </div>
                @php $taskCount++ @endphp
            </div>
            @endif
            @empty
            <div class ="col-12 text-center empty">
                <p>You are not assigned to any task.</p>
            </div>
        @endforelse
        </div>
    </div>

    <div class="col-12 mb-5">
        <header>
            <h1><i class="bi bi-calculator-fill"></i> Task Statistics</h1>
        </header>
	</div>

    <div class="col-12  d-flex justify-content-center">
        <div class = "row ">
            <div class = "col-4 mx-auto">
                <div class ="row">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statNumber" style="color: #2E9D7F;">{{$statusOpenCount = collect($tasks)->where('task.status', 'open')->count()}}</span>
                    </div>
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statInfo" style="color: #2E9D7F;">Open Tasks</span></div>
                    </div>
            </div>
            <div class = "col-4 mx-auto">
                <div class ="row">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statNumber"style="color: orange;">{{$statusCanceledCount = collect($tasks)->where('task.status', 'canceled')->count()}}</span>
                    </div>
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statInfo"style="color: orange;">Canceled Tasks</span></div>
                </div>
            </div>
            <div class = "col-4 mx-auto">
                <div class ="row">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statNumber" style="color: #ff3333;">{{$statusClosedCount = collect($tasks)->where('task.status', 'closed')->count()}}</span>
                    </div>
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <span class="statInfo" style="color: #ff3333;">Closed Tasks</span></div>
                    </div>
                </div>
            </div>
	    </div>
    </div>
</div>

@endsection
