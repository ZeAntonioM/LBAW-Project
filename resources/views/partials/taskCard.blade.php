<section class="taskCard">
    <ul>
        <li>
            <header>
                <h3><a href="{{route('task',['project'=>$project,'task'=>$task])}}">{{$task->title}}</a></h3>
                @if($task->status == 'open') <span class="status open"> <i class="fa-solid fa-folder-open"></i> Open </span>
                @elseif($task->status == 'closed') <span class="status closed"> <i class="fa-solid fa-folder-closed"></i> Closed </span>
                @else <span class="status cancelled"> <i class="fa-solid fa-ban"></i> Canceled </span>
                @endif
            </header>
        </li>
        <li class="deadLine"><i class="fa-regular fa-calendar"></i>
        @if($task->deadline) {{ date('d-m-Y', strtotime($task->deadline)) }}
            @else There is no deadline
            @endif
        </li>
    </ul>
    <h6>#{{$task->id}} Created by {{$task->created_by->name}} on {{ date('d-m-Y', strtotime($task->starttime)) }}</h6>
</section>