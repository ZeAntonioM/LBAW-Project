<?php

namespace App\Events;

use App\Models\Project;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteNotificationEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $user;
    public $description;
    public $date;
    public $seen;
    public $type;

    public function __construct(Project $project,User $user, $message)
    {
        $this->project = $project;
        $this->user = $user;
        $this->description = $message;
        $this->date = Now();
        $this->seen= False;
        $this->type = 'Invite';

    }

    public function broadcastOn()
    {
        return new Channel('user.'. $this->user->id);
    }
    public function broadcastAs() {
        return 'notification-user';
    }


}
