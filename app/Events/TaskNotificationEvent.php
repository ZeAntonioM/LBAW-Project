<?php

namespace App\Events;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class taskNotificationEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $task;
    public $description;
    public $date;
    public $seen;
    public $type;

    public function __construct(Project $project,Task $task, $message)
    {
        $this->project = $project;
        $this->description = $message;
        $this->date = Now();
        $this->seen= False;
        $this->task= $task;
        $this->type = 'Task';
    }

    public function broadcastOn()
    {
        return new Channel('task.'. $this->task->id);
    }
    public function broadcastAs() {
        return 'notification-task';
    }


}
