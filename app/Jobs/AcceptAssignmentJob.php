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

class AcceptAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user, $custom_message, $assignment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $custom_message, $assignment)
    {
        $this->connection = 'database';
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->assignment = $assignment;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'assignment' => $this->assignment);

        Mail::send('email.add_assignment', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Assignment Accepted');
          $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AcceptAssignmentEvent";
        $notification->notifiable_type = "App\Models\Assignment";
        $notification->notifiable_id = $this->user->id;
        $notification->message = $this->custom_message;
        $notification->data =  $this->assignment;
        $notification->save();
    }
}
