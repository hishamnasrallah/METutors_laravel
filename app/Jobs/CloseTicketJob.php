<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CloseTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $userid, $user, $custom_message, $ticket;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $ticket)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message =  $custom_message;
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Email to Student  ************\\
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;
        $email_subject = ' Update on support ticket no. ' . $this->ticket->ticket_id;

        $data = array('user' =>  $this->user, 'custom_message' =>  $custom_message, 'ticket' => $this->ticket);

        Mail::send('email.ticket_closed', $data, function ($message) use ($to_email, $email_subject) {
            $message->to($to_email)->subject($email_subject);
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\CloseTicketEvent";
        $notification->notifiable_type = "App\Models\Ticket";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->ticket;
        $notification->save();
    }
}
