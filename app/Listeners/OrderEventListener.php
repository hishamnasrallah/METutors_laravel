<?php

namespace App\Listeners;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = new Order();
        $order->user_id = $event->student_id;
        $order->course_id = $event->course_id;
        $order->transaction_id = $event->transaction_id;
        $order->payment_status = 'success';
        $order->save();
    }
}
