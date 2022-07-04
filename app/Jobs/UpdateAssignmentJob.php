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

class UpdateAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $assignment, $userid, $custom_message, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($assignment, $userid, $custom_message, $user)
    {
        $this->connection = 'database';
        $this->assignment = $assignment;
        $this->userid = $userid;
        $this->custom_message = $custom_message;
        $this->user = $user;
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
        $courseMessage = $this->course_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'assignment' => $this->assignment);

        Mail::send('email.add_assignment', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Assignment Updated');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending  Email ends **********//

        //********* Sending  Notifications **********//
        $notification = new Notification();
        $notification->type = "App\Events\UpdateAssignmentEvent";
        $notification->notifiable_type = "App\Models\Assignment";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->assignment;
        $notification->save();
    }
}
