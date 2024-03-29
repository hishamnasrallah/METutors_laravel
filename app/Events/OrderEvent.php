<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $course_id, $student_id, $transaction_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($course_id, $student_id, $transaction_id)
    {
        $this->course_id = $course_id;
        $this->student_id = $student_id;
        $this->transaction_id = $transaction_id;
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
        return 'order';
    }
}
