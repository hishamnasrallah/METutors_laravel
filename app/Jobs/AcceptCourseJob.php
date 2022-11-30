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

class AcceptCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course;
    public $userid;
    public $message;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course, $userid, $message, $user)
    {
        $this->connection = 'database';
        $this->course = $course;
        $this->userid = $userid;
        $this->message = $message;
        $this->user = $user;
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
        $courseMessage = $this->message;
        $to_email = $user_email;

        $teacher_grade = '';
        if ($this->user->role_name == 'teacher' || $this->user->role_name == 'admin') {
            $teacher = TeacherSubject::where('user_id', $this->user->id)
                ->whereNotNull('grade')
                ->max('grade');

            if ($teacher) {
                $teacher_grade = $teacher->grade;
            }
        }

        $course_days = [];
        $course_weekdays = [$this->course->weekdays];
        foreach ($course_weekdays as $course_weekday) {
            if ($course_weekday == 0) {
                $course_days[] = 'Sun';
            } elseif ($course_weekday == 1) {
                $course_days[] = 'Mon';
            } elseif ($course_weekday == 2) {
                $course_days[] = 'Tue';
            } elseif ($course_weekday == 3) {
                $course_days[] = 'Wed';
            } elseif ($course_weekday == 4) {
                $course_days[] = 'Thu';
            } elseif ($course_weekday == 5) {
                $course_days[] = 'Fri';
            } else {
                $course_days[] = 'Sat';
            }
        }

        $data = array('user' =>  $this->user, 'courseMessage' =>  $courseMessage, 'course' => $this->course, 'teacher_grade' => $teacher_grade, 'course_days' => $course_days);


        if ($this->user->role_name == 'admin') {
            Mail::send('email.course_accepted', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('New course accepted');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'teacher') {
            Mail::send('email.course_accepted', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Teaching a new course on MEtutors');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'student') {
            Mail::send('email.course_accepted', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('New course booking is approved');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AcceptCourse";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->message;
        $notification->data =  $this->course;
        $notification->save();
    }
}
