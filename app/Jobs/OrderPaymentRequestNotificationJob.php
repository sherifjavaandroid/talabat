<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseMessagingTrait;

class OrderPaymentRequestNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseMessagingTrait;


    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->loadLocale();
        $headings = "#{$this->order->code} " . __("Order Updated by vendor");
        $message = __("Vendor requesting payment for order");
        $this->sendFirebaseNotification(
            $this->order->user_id,
            $headings,
            $message,
            [],
            $onlyData = false,
        );
        $this->resetLocale();
    }
}
