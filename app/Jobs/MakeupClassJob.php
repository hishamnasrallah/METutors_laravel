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

class MakeupClassJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $class;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $class)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->class = $class;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'class' => $this->class);

        Mail::send('email.class', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Makeup class added!');
            $message->from('info@metutors.com', 'MeTutor');
        });
        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\MakeupClassEvent";
        $notification->notifiable_type = "App\Models\AcademicClass";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->class;
        $notification->save();
    }
}
