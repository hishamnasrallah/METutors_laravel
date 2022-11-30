<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassRescheduleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userid, $user, $custom_message, $class;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $class, $custom_message)
    {
        $this->userid = $userid;
        $this->user = $user;
        $this->class = $class;
        $this->custom_message = $custom_message;
       
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
        return 'class_rescheduled';
    }
}
