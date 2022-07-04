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

class AddSyllabusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $topic, $custom_message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $topic, $custom_message)
    {
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->topic = $topic;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'topic' => $this->topic);

        Mail::send('email.syllabus', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Syllabus Added');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AddSyllabusEvent";
        $notification->notifiable_type = "App\Models\Topic";
        $notification->notifiable_id = $this->user->id;
        $notification->message = $this->custom_message;
        $notification->data =  $this->topic;
        $notification->save();
    }
}
