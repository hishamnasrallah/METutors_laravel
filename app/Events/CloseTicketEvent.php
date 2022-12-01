<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CloseTicketEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment, $user, $custom_message, $ticket,$ticket_status;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $user, $custom_message, $ticket,$ticket_status)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->custom_message =  $custom_message;
        $this->ticket = $ticket;
        $this->ticket_status = $ticket_status;
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
        return 'close-ticket';
    }
}
