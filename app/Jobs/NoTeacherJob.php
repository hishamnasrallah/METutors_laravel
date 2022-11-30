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

class NoTeacherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $course;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $custom_message, $course)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->course = $course;
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

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'course' => $this->course);

        if ($this->user->role_name == 'admin') {
            Mail::send('email.no_teacher_course', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Course booked without a teacher - action needed');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        if ($this->user->role_name == 'student') {
            Mail::send('email.no_teacher_course', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('New course booking on MEtutors - order number XXX');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\NoTeacherJob";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
