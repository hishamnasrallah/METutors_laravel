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

class TeacherReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course, $userid, $message, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course, $userid, $courseMessagex   , $user)
    {
        $this->connection = 'database';
        $this->course = $course;
        $this->userid = $userid;
        $this->courseMessagex    = $courseMessagex ;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Course Reminder Email  ************\\
        $user_email = $this->user->email;
        $courseMessage = $this->courseMessage;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $this->course);

        Mail::send('email.course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course has not accepted yet');
           $message->from(env('MAIL_FROM_ADDRESS', 'metutorsmail@gmail.com'), 'MEtutors');
        });
        //********* Sending Course Reminder Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\TeacherReminderEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->message;
        $notification->data =  $this->course;
        $notification->save();

        $this->course->warning_count = $this->course->warning_count + 1;
        $this->course->update();
    }
}
