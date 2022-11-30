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

class NewCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public  $user, $custom_message, $certificate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $custom_message, $certificate)
    {
        $this->user = $user;
        $this->custom_message = $custom_message;
        $this->certificate = $certificate;
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

        $data = array('user' =>  $this->user, 'custom_message' =>  $custom_message, 'certificate' => $this->certificate);

        Mail::send('email.new_certificate', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course certificate is ready for download');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        // //******** Email ends **********//

        //******** Notification ************
        $notification = new Notification();
        $notification->type = "App\Events\NewCertificateEvent";
        $notification->notifiable_type = "App\Models\Certificate";
        $notification->notifiable_id = $this->user->id;
        $notification->message =  $this->custom_message;
        $notification->data =  $this->certificate;
        $notification->save();
    }
}
