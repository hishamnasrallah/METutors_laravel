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

class CourseBookingJob implements ShouldQueue
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

        $teacher_grade = '';
        if ($this->user->role_name == 'teacher' || $this->user->role_name == 'admin') {
            $teacher = TeacherSubject::where('user_id', $this->course->teacher->id)
            ->whereNotNull('grade')
            ->max('grade');

            if ($teacher) {
                $teacher_grade = $teacher->grade;
            }
        }

        $mail_subject = 'New course booking on MEtutors - order number ' . $this->course->order->booking_id;
        $data = array('user' =>  $this->user, 'course' => $this->course, 'grade' => $teacher_grade);

        if ($this->user->role_name == 'admin') {
            Mail::send('email.book_course', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('New course booking');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        if ($this->user->role_name == 'teacher') {
            Mail::send('email.book_course', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('New course booking - action needed');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        if ($this->user->role_name == 'student') {
            Mail::send('email.book_course', $data, function ($message) use ($to_email,$mail_subject) {
                $message->to($to_email)->subject($mail_subject);
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }


        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\CourseBookingEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
