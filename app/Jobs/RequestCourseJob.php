<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RequestCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $custom_data, $email, $custom_message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($custom_data, $email, $custom_message)
    {
        $this->connection = 'database';
        $this->custom_data = $custom_data;
        $this->custom_message = $custom_message;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //*********** Sending Email to Student  ************\\
        $user_email = $this->email;
        $custom_message = $this->custom_message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'custom_data' => $this->custom_data);

        Mail::send('email.request_course', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Request!');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        // //******** Email ends **********//
    }
}
