<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use App\Models\Payment;

class CancelPendingPaymentOrder extends Command
{
    protected $signature = 'order:cancel-pending-payments';

    protected $description = 'Cancel pending payment orders older than set minutes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $mins = (int) setting('finance.orderOnlinePaymentTimeout', 10);
        if ($mins <= 0) {
            return;
        }

        $timeAgo = now()->subMinutes($mins);
        // logger("Time ago: " . $timeAgo);
        $payments = Payment::whereHas('order', function ($q) {
            return $q->whereHas('payment_method', function ($q) {
                return $q->whereNotIn('slug', ['cash', 'wallet', 'offline']);
            });
        })
            ->where('status', 'pending')
            ->where('updated_at', '<', $timeAgo)
            ->get();

        foreach ($payments as $payment) {

            //
            if ($payment->status != 'pending') {
                continue;
            }

            // Cancel the order here
            $order = Order::where('id', $payment->order_id)->first();
            if ($order) {
                $order->payment_status = 'failed';
                $order->save();
                $order->setStatus('cancelled', 'Payment timeout');
            }

            //CANCEL PAYMENT
            $payment->status = 'failed';
            $payment->save();
        }

        $this->info(count($payments) . " pending payment orders older than $mins minutes have been cancelled.");
    }
}
