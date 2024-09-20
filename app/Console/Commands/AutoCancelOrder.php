<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Traits\FirebaseDBTrait;
use Illuminate\Console\Command;

class AutoCancelOrder extends Command
{

    use FirebaseDBTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending order when the time is right';

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
        if(setting('autoCancelPendingOrderTime', 30) == "0"){
            return;
        }
        //get orders pending for more the ``autoCancelPendingOrderTime``
        $cancelTime = setting('autoCancelPendingOrderTime', 30);
        $timeZone = setting('timeZone', 'UTC');
        $expireDateTime = \Carbon\Carbon::now($timeZone)->subMinutes($cancelTime)->format('Y-m-d H:i:s');
        //get orders pending for more the ``autoCancelPendingOrderTime``
        $orders = Order::currentStatus('pending')->whereDoesntHave('taxi_order')->where('updated_at', '<=', $expireDateTime)->limit(20)->get();
        
        foreach ($orders as $order) {
            $order->setStatus('cancelled');
            $this->deleteFirestoreOrderNode($order);
        }


    }
}
