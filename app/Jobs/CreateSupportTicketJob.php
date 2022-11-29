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

class CreateSupportTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user, $ticket, $custom_message, $created_by;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $ticket, $custom_message, $created_by)
    {
        $this->connection = 'database';
        $this->user = $user;
        $this->ticket = $ticket;
        $this->custom_message = $custom_message;
        $this->created_by = $created_by;
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
        $ticket = json_decode($this->ticket);

        $student_subject = 'Support ticket no. ' . $ticket->ticket_id . ' submitted successfully';
        $teacher_subject = 'New support ticket no. ' . $ticket->ticket_id . ' submitted successfully';
        $admin_subject =  'A new support ticket no. ' . $ticket->ticket_id . ' has been created';

        $data = array('user' => $this->user, 'custom_message' =>  $custom_message, 'ticket' => $this->ticket, 'created_by' => $this->created_by);

        if ($this->created_by == 'student') {
            if ($this->user->role_name == 'student') {
                Mail::send('email.create_support_ticket', $data, function ($message) use ($to_email, $student_subject) {
                    $message->to($to_email)->subject($student_subject);
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }

        if ($this->created_by == 'teacher') {
            if ($this->user->role_name == 'teacher') {
                Mail::send('email.create_support_ticket', $data, function ($message) use ($to_email, $teacher_subject) {
                    $message->to($to_email)->subject($teacher_subject);
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
            }
        }

        if ($this->user->role_name == 'admin') {
            Mail::send('email.create_support_ticket', $data, function ($message) use ($to_email, $admin_subject) {
                $message->to($to_email)->subject($admin_subject);
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
        }
        // //******** Email ends **********//

        //******** Notification ************
        $notification = new Notification();
        $notification->type = "App\Events\CreateSupportTicketEvent";
        $notification->notifiable_type = "App\Models\Ticket";
        $notification->notifiable_id = $this->user->id;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->ticket;
        $notification->save();
    }
}
