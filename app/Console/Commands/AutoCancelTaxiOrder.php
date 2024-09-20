<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Traits\FirebaseDBTrait;
use Illuminate\Console\Command;

class AutoCancelTaxiOrder extends Command
{

    use FirebaseDBTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxi:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending taxi order when the time is right';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        $cancelTime = setting('taxi.cancelPendingTaxiOrderTime', 2);
        if($cancelTime == "0"){
            return;
        }

        $expireDateTime = \Carbon\Carbon::now(setting('timeZone', 'UTC'))->subMinutes($cancelTime)->format('Y-m-d H:i:s');
        //get orders pending for more the ``autoCancelPendingOrderTime``
        $orders = Order::currentStatus('pending')->whereHas('taxi_order')->where('updated_at', '<=', $expireDateTime)->limit(20)->get();

        foreach ($orders as $order) {
            $order->setStatus('cancelled');
            $this->deleteFirestoreOrderNode($order);
        }
    }
}
