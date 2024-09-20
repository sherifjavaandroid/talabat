<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseMessagingTrait;

class OrderStatusNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait, FirebaseMessagingTrait;


    public $order;
    public $notificationType = 0;
    public $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $type = 0, $status = null)
    {
        $this->order = $order;
        $this->notificationType = $type;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Type
        /**
         * 1 - Regulater Status change
         * 2 - Taxi status change
         * 3 - Driver notification
         */
        // logger("notificationType", [
        //     $this->notificationType,
        //     $this->order->code
        // ]);
        switch ($this->notificationType) {
            case 1:
                $this->sendOrderStatusChangeNotification($this->order, $this->status);
                break;
            case 2:
                $this->sendTaxiOrderStatusChangeNotification($this->order);
                break;
            case 3:
                $this->sendOrderNotificationToDriver($this->order);
                break;

            default:
                $this->sendOrderStatusChangeNotification($this->order, $this->status);
                break;
        }
    }
}