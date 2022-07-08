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

class InterviewRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $interview_request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $interview_request)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->interview_request = $interview_request;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview_request' => $this->interview_request);

        Mail::send('email.interview_request', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Interview Request!');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        // //******** Email ends **********//

        //******** Notification ************
        $notification = new Notification();
        $notification->type = "App\Events\IntrviewRequestEvent";
        $notification->notifiable_type = "App\Models\Interview";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->interview_request;
        $notification->save();
    }
}
