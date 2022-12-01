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

class RejectAssignmentJob implements ShouldQueue
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

        $data = array('user' =>  $this->user, 'custom_message' =>  $custom_message, 'assignment' => $this->assignment);

        if ($this->user->role_name == 'student') {
            Mail::send('email.reject_assignment', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('An assignment on your course has been rejected');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'teacher') {
            Mail::send('email.reject_assignment', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('An assignment has been rejected');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\RejectAssignmentEvent";
        $notification->notifiable_type = "App\Models\Assignment";
        $notification->notifiable_id = $this->user->id;
        $notification->message = $this->custom_message;
        $notification->data =  $this->assignment;
        $notification->save();
    }
}
