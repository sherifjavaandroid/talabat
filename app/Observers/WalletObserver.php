<?php

namespace App\Observers;

use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletObserver
{

    // public function creating(Wallet $model)
    // {
    //     $this->walletTopupRevised($model);
    // }

    // public function updating(Wallet $model)
    // {
    //     $this->walletTopupRevised($model);
    // }


    // public function walletTopupRevised(Wallet $model)
    // {

    //     $currentRouteName = \Route::currentRouteName();
    //     $currentRouteUrl = url();
    //     if (!request()->wantsJson()) {
    //         $currentRouteUrl = \Cache::get('wallet_current_url', '');
    //     }
    //     //
    //     if ($currentRouteName == 'wallet.topup.callback' || $currentRouteUrl == route('wallet.transactions')) {
    //         // logger("updating ==> from callback");
    //         $oldBalance = $model->getOriginal('balance');
    //         $newBalance = $model->balance;
    //         $balanceDif = $newBalance - $oldBalance;
    //         //
    //         if ($balanceDif > 0) {
    //             $walletTopupPercentage = setting('walletTopupPercentage', 100);
    //             if(empty($walletTopupPercentage)){
    //                 $walletTopupPercentage = 100;
    //             }
    //             $topupAmount = ($walletTopupPercentage / 100) * $balanceDif;
    //             $model->balance -= $balanceDif;
    //             $model->balance += $topupAmount;
    //             //update the wallet transaction amount
    //             $this->updateWalletTransaction($topupAmount);
    //             // logger("Wallet data", [$oldBalance, $newBalance, $balanceDif, $topupAmount]);
    //         }
    //     }
    // }

    // public function updateWalletTransaction($amount)
    // {
    //     $topUpRef = request()->code;
    //     if(empty($topUpRef)){
    //         $topUpRef =  request()->session()->get('wallet_transaction_code');
    //     }
    //     $walletTransaction = WalletTransaction::where('ref', $topUpRef)
    //         ->orWhere('session_id', $topUpRef)
    //         ->first();
    //     //
    //     if (!empty($walletTransaction)) {
    //         $walletTransaction->amount = $amount;
    //         $walletTransaction->save();
    //     }
    // }
}
