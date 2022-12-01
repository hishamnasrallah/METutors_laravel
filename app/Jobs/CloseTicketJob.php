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

class CloseTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $comment, $user, $custom_message, $ticket, $ticket_status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comment, $user, $custom_message, $ticket, $ticket_status)
    {
        $this->connection = 'database';
        $this->comment = $comment;
        $this->user = $user;
        $this->custom_message =  $custom_message;
        $this->ticket = $ticket;
        $this->ticket_status = $ticket_status;
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
        $student_subject = ' Update on support ticket no. ' . $this->ticket->ticket_id;
        $admin_subject = ' Support ticket no. ' . $this->ticket->ticket_id . ' has been resolved';
        $teacher_subject = 'Support ticket no. ' . $this->ticket->ticket_id . ' has been resolved';

        $data = array('comment' => $this->comment, 'user' =>  $this->user, 'custom_message' =>  $custom_message, 'ticket' => $this->ticket, 'ticket_status' => $this->ticket_status);

        if ($this->user->role_name == 'admin') {
            Mail::send('email.ticket_closed', $data, function ($message) use ($to_email, $admin_subject) {
                $message->to($to_email)->subject($admin_subject);
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'teacher') {
            Mail::send('email.ticket_closed', $data, function ($message) use ($to_email, $teacher_subject) {
                $message->to($to_email)->subject($teacher_subject);
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        if ($this->user->role_name == 'student') {
            Mail::send('email.ticket_closed', $data, function ($message) use ($to_email, $student_subject) {
                $message->to($to_email)->subject($student_subject);
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }

        // //******** Email ends **********//

        //Notification
        $notification = new Notification();
        $notification->type = "App\Events\CloseTicketEvent";
        $notification->notifiable_type = "App\Models\Ticket";
        $notification->notifiable_id = $this->user->id;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->ticket;
        $notification->save();
    }
}
