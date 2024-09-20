<?php

namespace App\Console\Commands;

use App\Models\WalletTransaction;
use Illuminate\Console\Command;

class CancelPendingPaymentWalletTransaction extends Command
{
    protected $signature = 'wallet:cancel-pending-transactions';

    protected $description = "Cancel pending wallet transactions older than given minutes.";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $mins = (int) setting('finance.walletTopupPaymentTimeout', 10);
        if ($mins <= 0) {
            return;
        }

        $timeAgo = now()->subMinutes($mins);
        // logger("Time ago: " . $timeAgo);
        $walletTransactions = WalletTransaction::where(function ($query) {
            return $query->whereHas('payment_method', function ($q) {
                return $q->whereNotIn('slug', ['cash', 'wallet', 'offline']);
            })->orWheredoesntHave('payment_method');
        })
            ->where('status', 'pending')
            ->where('updated_at', '<', $timeAgo)
            ->get();

        foreach ($walletTransactions as $walletTransaction) {
            // logger("About to fail the wallet transaction: " . $walletTransaction->id);
            // logger("Current status: " . $walletTransaction->status);
            if ($walletTransaction->status == 'pending') {
                //CANCEL WALLET TRANSACTION
                $walletTransaction->status = 'failed';
                $walletTransaction->save();
            }
        }

        $this->info(count($walletTransactions) . " pending wallet transactions older than $mins minutes have been cancelled.");
    }
}
