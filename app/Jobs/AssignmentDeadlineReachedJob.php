<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AssignmentDeadlineReachedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $course, $user, $custom_message, $assignment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course, $user, $custom_message, $assignment)
    {
        $this->course = $course;
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
        $order = Order::where('course_id', $this->course->id)
            ->where('user_id', $this->course->student_id)
            ->first();
        //********* Sending Email **********
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('assignment' => $this->assignment, 'course' => $this->course, 'user' => $this->user, 'order' => $order);

        if ($this->user->role_name == 'student') {
            Mail::send('email.assignmet_deadline_reached', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Course assignment deadline missed');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'teacher') { {
                Mail::send('email.assignmet_deadline_reached', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Course assignment deadline missed');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }

            //********* Sending Email ends **********

            //********* Realtime Notification **********
            $notification = new Notification();
            $notification->type = "App\Events\AssignmentDeadlineReachedEvent";
            $notification->notifiable_type = "App\Models\Assignment";
            $notification->notifiable_id = $this->user->id;
            $notification->message = $this->custom_message;
            $notification->data =  $this->assignment;
            $notification->save();
        }
    }
}
