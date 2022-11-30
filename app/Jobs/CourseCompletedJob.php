<?php

namespace App\Jobs;

use App\Models\Notification;
use App\TeacherSubject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CourseCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user, $custom_message, $course;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $custom_message, $course)
    {
        $this->connection = 'database';
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

        $data = array('user' =>  $this->user, 'course' => $this->course);

        Mail::send('email.course_completed', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Successful course completion');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });

        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\CourseCompletedEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->user->id;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
