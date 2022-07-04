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

class RejectCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $course;
    public $userid;
    public $message;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course, $userid, $message, $user)
    {
        $this->connection = 'database';
        $this->course = $course;
        $this->userid = $userid;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Rejection Email  ************\\
        $user_email = $this->user->email;
        $courseMessage = $this->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $this->course);

        Mail::send('email.course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Rejected');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending Rejection Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\RejectCourseEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
