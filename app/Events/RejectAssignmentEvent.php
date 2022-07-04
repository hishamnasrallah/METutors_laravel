<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RejectAssignmentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user, $custom_message, $assignment;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $custom_message, $assignment)
    {
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->assignment = $assignment;
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
        return 'assignment-rejected';
    }
}
