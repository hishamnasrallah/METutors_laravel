<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class testJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->connection = 'database';
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //********* Sending Email **********
        $data = array('email' =>  "test@gmail.com", 'courseMessage' =>  "test message", 'course' => "courseid:1");
        Mail::send('email.course', $data, function ($message) {
            $message->to($this->details['email'])->subject('Test Mail');
           $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
    }
}
