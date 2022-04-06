<?php

namespace App\Listeners;

use App\Events\UpdateProfile;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UpdateProfileListener
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
    public function handle(UpdateProfile $event)
    {
        //********* Sending Email **********
        $user_email = $event->user->email;
        $profileMessage = "Profie Updated Successfully!";
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'profileMessage' =>  $profileMessage, 'user' => $event->user);

        Mail::send('email.profile', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Profie Updated');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\UpdateProfile";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = $event->userid;
        $notification->message = $event->message;
        $notification->data =  $event->user;
        $notification->save();
    }
}
