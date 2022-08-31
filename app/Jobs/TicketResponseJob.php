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

class TicketResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $comment)
    {
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message =  $custom_message;
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //*********** Sending Email to User  ************\\
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'comment' => $this->comment);

        Mail::send('email.ticket_response', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Ticket Response');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending  Email ends **********//

        //********* Sending  Notifications **********//
        $notification = new Notification();
        $notification->type = "App\Events\TicketResponseEvent";
        $notification->notifiable_type = "App\Models\Ticket";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->comment;
        $notification->save();
    }
}
