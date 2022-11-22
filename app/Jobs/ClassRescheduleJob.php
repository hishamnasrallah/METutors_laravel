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

class ClassRescheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $user, $custom_message, $class, $rescheduled_by;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $user, $class, $custom_message, $rescheduled_by)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->class = $class;
        $this->rescheduled_by = $rescheduled_by;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Cancalation Email to Student  ************\\
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('user' =>  $this->user, 'custom_message' =>  $custom_message, 'class' => $this->class);

        if ($this->rescheduled_by == 'student') {
            if ($this->user->role_name == 'teacher') {
                Mail::send('email.student_reschedule_class', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Successfully rescheduled a class');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }

            if ($this->user->role_name == 'student') {
                Mail::send('email.student_reschedule_class', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Class rescheduled successfully by a tutor');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }
        if ($this->rescheduled_by == 'teacher') {
            if ($this->user->role_name == 'teacher') {
                Mail::send('email.teacher_reschedule_class', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Successfully rescheduled a class');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }

            if ($this->user->role_name == 'student') {
                Mail::send('email.teacher_reschedule_class', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Class rescheduled successfully by a tutor');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }


        // //********* Sending Cancalation Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\ClassRescheduleEvent";
        $notification->notifiable_type = "App\Models\AcademicClass";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->class;
        $notification->save();
    }
}
