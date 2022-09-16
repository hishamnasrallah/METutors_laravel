<?php

namespace App\Listeners;

use App\Events\CourseBooked;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseBookedListener
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
    public function handle(CourseBooked $event)
    {
        $notification = new Notification();
        $notification->type = "App\Events\CourseBooked";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $event->userid;
        $notification->message = "Course booked successfully";
        $notification->save();
    }
}
