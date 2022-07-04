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

class SubmittAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $assignment;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $assignment)
    {
        $this->connection = 'database';
        $this->assignment = $assignment;
        $this->custom_message = $custom_message;
        $this->user = $user;
        $this->userid = $userid;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'assignment' => $this->assignment);

        Mail::send('email.add_assignment', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Assignment Submitted');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending  Email ends **********//

        //********* Sending  Notifications **********//
        $notification = new Notification();
        $notification->type = "App\Events\SubmitAssignmentEvent";
        $notification->notifiable_type = "App\Models\Assignment";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->assignment;
        $notification->save();
    }
}
