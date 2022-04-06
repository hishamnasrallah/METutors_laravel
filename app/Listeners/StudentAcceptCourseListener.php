<?php

namespace App\Listeners;

use App\Events\StudentAcceptCourse;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StudentAcceptCourseListener
{
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
    public function handle(StudentAcceptCourse $event)
    {
        $notification = new Notification();
        $notification->type = "App\Events\StudentAcceptCourse";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $event->userid;
        $notification->message = "Teacher Accepted Course";
        $notification->data =  $event->course;
        $notification->save();
    }
}
