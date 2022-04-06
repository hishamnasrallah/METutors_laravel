<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptCourse implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    use Queueable;

    public $course;
    public $userid;
    public $message;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($course, $userid, $message, $user)
    {
        $this->course = $course;
        $this->userid = $userid;
        $this->message = $message;
        $this->user = $user;
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
}
