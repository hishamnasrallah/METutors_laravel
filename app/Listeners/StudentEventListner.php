<?php

namespace App\Listeners;

use App\Events\StudentRegister;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StudentEventListner
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
    public function handle(StudentRegister $event)
    {
        // return $event;
       $notification=new Notification();
       If($event->userrole=="1"){
        $notification->type="App\Events\StudentRegister";
       }
       If($event->userrole=="3"){
        $notification->type="App\Events\TeacherRegister";
       }
       $notification->notifiable_type="App\Models\User";
       $notification->notifiable_id=$event->userid;
       $notification->data=$event->username;
       $notification->save();
    }
}
