<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ManageOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage order when the time is overdue';

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
        $today = Carbon::today();
        $hoursFromNow = Carbon::now()->addHours(setting('minScheduledTime', 2))->toTimeString();

        //get orders scheduled for today with the next 2hours
        $orders = Order::otherCurrentStatus(['failed', 'cancelled', 'delivered'])
            ->whereDate('pickup_date', "<", $today)
            ->whereTime('pickup_time', '<=', $hoursFromNow)->get();

        foreach ($orders as $order) {
            $order->setStatus('cancelled');
            $order->verification_code = Str::random(5);
            $order->save();
        }


        //
        $orders = Order::otherCurrentStatus(['failed', 'cancelled', 'delivered'])
            ->whereDate('created_at', "<", $today);

        foreach ($orders as $order) {
            $order->setStatus('cancelled');
            $order->verification_code = Str::random(5);
            $order->save();
        }
    }
}
