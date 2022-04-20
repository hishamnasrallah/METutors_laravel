<?php

namespace App\Listeners;

use App\Events\AcceptAssignment;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class AcceptAssignmentListener
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
    public function handle(AcceptAssignment $event)
    {
        //********* Sending Email **********
        $user_email = $event->user->email;
        $assignmentMessage = $event->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'assignmentMessage' =>  $assignmentMessage, 'assignment' =>   $event->assignment);

        Mail::send('email.assignment', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Assignment Accepted');
            $message->from(env('MAIL_FROM_ADDRESS', 'metutorsmail@gmail.com'), 'MeTutor');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AcceptAssignment";
        $notification->notifiable_type = "App\Models\UserAssignment";
        $notification->notifiable_id = $event->user->id;
        $notification->message = $event->message;
        $notification->data =  $event->assignment;
        $notification->save();
    }
}
