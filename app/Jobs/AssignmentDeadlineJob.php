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

class AssignmentDeadlineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course, $user, $custom_message, $assignment,$reminder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course, $user, $custom_message, $assignment,$reminder)
    {
        $this->course = $course;
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->assignment = $assignment;
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::where('course_id', $this->course->id)->where('user_id',$this->course->student_id)->first();
        //********* Sending Email **********
        $user_email = $this->user->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('assignment' => $this->assignment,'course' => $this->course,'user' => $this->user,'reminder' => $this->reminder,'order' => $order);

        if($this->reminder == 1){
            Mail::send('email.assignment_deadline', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('First reminder - Course assignment due tomorrow');
               $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }else{
            Mail::send('email.assignment_deadline', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject(' Second reminder - Course assignment due today');
               $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
       
        //********* Sending Email ends **********

        //********* Realtime Notification **********
        $notification = new Notification();
        $notification->type = "App\Events\AddResourceEvent";
        $notification->notifiable_type = "App\Models\Resource";
        $notification->notifiable_id = $this->user->id;
        $notification->message = $this->custom_message;
        $notification->data =  $this->assignment;
        $notification->save();
    }
}
