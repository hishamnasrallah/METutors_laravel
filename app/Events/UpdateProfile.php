<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateProfile
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $userid;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $userid, $message)
    {
        $this->user = $user;
        $this->userid = $userid;
        $this->message = $message;
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
        return 'profile_updated';
    }
}
