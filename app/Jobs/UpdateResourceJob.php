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

class UpdateResourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $custom_message, $resource, $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $custom_message, $resource, $user)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->custom_message = $custom_message;
        $this->resource = $resource;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //********* Sending Email **********
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'resource' => $this->resource);

        Mail::send('email.resource', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Resource Updated');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\UpdateResourceEvent";
        $notification->notifiable_type = "App\Models\Resource";
        $notification->notifiable_id = $this->user->id;
        $notification->message = $this->custom_message;
        $notification->data =  $this->resource;
        $notification->save();
    }
}
