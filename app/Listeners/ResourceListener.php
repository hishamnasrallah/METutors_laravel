<?php

namespace App\Listeners;

use App\Events\AddResource;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResourceListener
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
    public function handle(AddResource $event)
    {
        //********* Sending Email **********
        $user_email = $event->user->email;
        $resourceMessage = $event->resourceMessage;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'resourceMessage' =>  $resourceMessage, 'resource' =>   $event->resource);

        Mail::send('email.resource', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Resource Added');
           $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AddResource";
        $notification->notifiable_type = "App\Models\Resource";
        $notification->notifiable_id = $event->user->id;
        $notification->message = $event->resourceMessage;
        $notification->data =  $event->resource;
        $notification->save();
    }
}
