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

class RefundCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id, $user, $course_message, $refund, $course;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $user, $course_message, $refund, $course)
    {
        $this->connection = 'database';
        $this->user_id = $user_id;
        $this->user_id = $user;
        $this->course_message = $course_message;
        $this->refund = $refund;
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending refund Email  ************\\
        $user_email = $this->user->email;
        $courseMessage = $this->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'courseMessage' =>  $courseMessage, 'course' => $this->course);

        Mail::send('email.refundcourse', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Refunded');
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });
        //********* Sending refund Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\RefundCourseEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->course_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
