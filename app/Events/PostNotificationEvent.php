<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostNotificationEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $description;
    public $date;
    public $seen;
    public $type ;

    public function __construct(Project $project, $message)
    {
        $this->project = $project;
        $this->description = $message;
        $this->date = Now();
        $this->seen= False;
        $this->type='Forum';
    }

    public function broadcastOn()
    {
        return new Channel('project.'. $this->project->id);
    }
    public function broadcastAs() {
        return 'notification-project';
    }


}
