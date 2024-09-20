<?php

namespace App\Jobs;

use App\Models\UserToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseMessagingTrait;

class OrderPaymentStatusChangeNotificationJob implements ShouldQueue
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
        $headings = "#{$this->order->code} " . __("Order Payment Update");
        $message = __("Order payment status changed:") . " " . __($this->order->payment_status);
        //customer
        $this->sendFirebaseNotification(
            $this->order->user_id,
            $headings,
            $message,
            [],
            $onlyData = false,
        );
        //
        $this->sendFirebaseNotification(
            "v_" . $this->order->vendor_id,
            $headings,
            $message,
            [],
            $onlyData = false,
        );
        //
        $this->resetLocale();
    }
}
