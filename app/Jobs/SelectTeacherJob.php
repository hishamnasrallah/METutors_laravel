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

class SelectTeacherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $course, $custom_message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $course, $custom_message)
    {

        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->course = $course;
        $this->custom_message = $custom_message;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'course' => $this->course);

        Mail::send('email.cancelled_course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Teacher Selected! Wait for Teacher Approval!');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\SelectTeacherEvent";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}