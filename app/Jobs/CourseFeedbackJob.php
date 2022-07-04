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

class CourseFeedbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $custom_message, $user, $feedback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $custom_message, $user, $feedback)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->custom_message = $custom_message;
        $this->user = $user;
        $this->feedback = $feedback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending refund Email  ************\\
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'feedback' => $this->feedback);

        Mail::send('email.refundcourse', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Feedback');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending refund Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\CourseFeedbackEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->custom_message;
        $notification->data =  $this->feedback;
        $notification->save();
    }
}
