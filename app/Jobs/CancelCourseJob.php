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

class CancelCourseJob implements ShouldQueue
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
    public function __construct($course, $userid, $course_message, $user)
    {
        
        $this->course = $course;
        $this->userid = $userid;
        $this->course_message = $course_message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Cancalation Email to Student  ************\\
        $user_email = $this->user->email;
        $courseMessage = $this->course_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $this->course);

        Mail::send('email.course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Canceled');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        // //********* Sending Cancalation Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\CancelCourse";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->course_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
