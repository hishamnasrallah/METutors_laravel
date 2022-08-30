<?php

namespace App\Listeners;

use App\Events\RejectCourse;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RejectCourseListener implements ShouldQueue
{
    public $connection = 'database';
    public $queue = 'listeners';
    public $delay = 60;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RejectCourse $event)
    {
        //*********** Sending Rejection Email  ************\\
        $user_email = $event->user->email;
        $courseMessage = $event->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $event->course);

        Mail::send('email.course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Rejected');
            $message->from(env('MAIL_FROM_ADDRESS', 'metutorsmail@gmail.com'), 'MEtutors');
        });
        //********* Sending Rejection Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\RejectCourse";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $event->userid;
        $notification->message = $event->message;
        $notification->data =  $event->course;
        $notification->save();
    }
}


