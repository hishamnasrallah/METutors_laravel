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

class AcceptDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $userid, $user, $user_meta, $custom_message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $user_meta, $custom_message)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->user_meta = $user_meta;
        $this->custom_message = $custom_message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->user->role_name == 'teacher') {
            //*********** Sending Email to Student  ************\\
            $user_email = $this->user->email;
            $custom_message = $this->custom_message;
            $to_email = $user_email;

            $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user_meta' => $this->user_meta);

            Mail::send('email.accept_document', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Documents Accepted!');
                $message->from('metutorsmail@gmail.com', 'MeTutor');
            });
            // //******** Email ends **********//
        }

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\AcceptDocumentEvent";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->user_meta;
        $notification->save();
    }
}
