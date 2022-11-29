<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateSupportTicketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user, $ticket, $custom_message, $created_by;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $ticket, $custom_message, $created_by)
    {
        $this->user = $user;
        $this->ticket = $ticket;
        $this->custom_message = $custom_message;
        $this->created_by = $created_by;
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
        return 'create-support-ticket';
    }
}
