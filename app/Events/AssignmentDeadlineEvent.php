<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignmentDeadlineEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $course, $user, $custom_message, $assignment,$reminder;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($course, $user, $custom_message, $assignment,$reminder)
    {
        $this->course = $course;
        $this->user=$user;
        $this->custom_message=$custom_message;
        $this->assignment= $assignment;
        $this->reminder= $reminder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['MeTutors'];
    }

    public function broadcastAs()
    {
        return 'assignment-deadline';
    }
}
