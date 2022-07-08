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

class UnBlockUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $user_data, $custom_message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $user_data, $custom_message)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user =  $user;
        $this->user_data =  $user_data;
        $this->custom_message =  $custom_message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        //*********** Sending Email to Student  ************\\
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user_data' => $this->user_data);

        Mail::send('email.block', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('User UnBlocked!');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\UnBlockUserEvent";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->user_data;
        $notification->save();
    }
}
