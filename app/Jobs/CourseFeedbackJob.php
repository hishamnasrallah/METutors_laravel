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

class CourseFeedbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userid, $custom_message, $user, $feedback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid, $custom_message, $user, $feedback)
    {
        $this->connection = 'database';
        $this->userid = $userid;
        $this->custom_message = $custom_message;
        $this->user = $user;
        $this->feedback = $feedback;
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
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $mail_subject = $this->feedback->sender->first_name . ' has left you feedback on MEtutors';
        $data = array('email' =>  $user_email, 'user' =>  $this->user, 'feedback' => $this->feedback);
        //If student submits a feedback for teacher
        if ($this->feedback->sender->role_name == 'student') {
            if ($this->user->role_name == 'student') {
                Mail::send('email.new_student_feedback', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Your feedback has been submitted successfully');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
            if ($this->user->role_name == 'teacher') {
                Mail::send('email.new_student_feedback', $data, function ($message) use ($to_email, $mail_subject) {
                    $message->to($to_email)->subject($mail_subject);
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }
        //If teacher submits a feedback for student
        else {
            $mail_subject = $this->feedback->sender->first_name.' has left you feedback on MEtutors';
            if ($this->user->role_name == 'student') {
                Mail::send('email.new_teacher_feedback', $data, function ($message) use ($to_email, $mail_subject) {
                    $message->to($to_email)->subject($mail_subject);
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
            if ($this->user->role_name == 'teacher') {
                Mail::send('email.new_teacher_feedback', $data, function ($message) use ($to_email) {
                    $message->to($to_email)->subject('Your feedback has been submitted successfully');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }

        //********* Sending refund Email ends **********//

        $notification = new Notification();
        $notification->type = "App\Events\CourseFeedbackEvent";
        $notification->notifiable_type = "App\Models\Course";
        $notification->notifiable_id = $this->userid;
        $notification->message = $this->custom_message;
        $notification->data =  $this->feedback;
        $notification->save();
    }
}
