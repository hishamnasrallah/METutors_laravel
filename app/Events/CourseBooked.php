<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseBooked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $userid;
    public $userrole;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username, $userid, $userrole)
    {
        $this->username = $username;
        $this->userid = $userid;
        $this->userrole = $userrole;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        return "Course-Booked";
    }
}
