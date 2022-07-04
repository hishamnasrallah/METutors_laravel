<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateResourceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userid, $custom_message, $resource, $user;
 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userid, $custom_message, $resource, $user)
    {
        $this->userid = $userid;
        $this->custom_message = $custom_message;
        $this->resource = $resource;
        $this->user = $user;
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
        return 'update-resource';
    }
}
