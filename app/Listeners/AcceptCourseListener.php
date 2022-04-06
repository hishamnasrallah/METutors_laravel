<?php

namespace App\Listeners;

use App\Events\AcceptCourse;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class AcceptCourseListener implements ShouldQueue
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
    public function handle(AcceptCourse $event)
    {
        //********* Sending Email **********
        $user_email = $event->user->email;
        $courseMessage = $event->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $event->course);

        Mail::send('email.course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Accepted');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AcceptCourse";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $event->userid;
        $notification->message = $event->message;
        $notification->data =  $event->course;
        $notification->save();
    }
}
