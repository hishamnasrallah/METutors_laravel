<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddResource
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $resourceMessage;
    public $resource;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($resourceMessage, $resource, $user)
    {
        $this->resourceMessage = $resourceMessage;
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
        return 'resource';
    }
}
