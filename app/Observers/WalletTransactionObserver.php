<?php

namespace App\Observers;

use App\Models\WalletTransaction;

class WalletTransactionObserver
{

    public function creating(WalletTransaction $model)
    {
        $this->walletTopupRevised($model);
    }

    public function updating(WalletTransaction $model)
    {
        $this->walletTopupRevised($model);
    }


    public function walletTopupRevised(WalletTransaction $model)
    {
        //if status is successful
        if ($model->status != 'successful' || !$model->isDirty('status')) {
            return;
        } else if ($model->is_credit == null || $model->is_credit == false) {
            return;
        }
        //only allow pass if the transaction is topup
        $isTopup = $model->reason == __('Topup');
        //if ref starts with "tp_"
        $isTopup = $isTopup || \Str::startsWith($model->ref, 'tp_');
        if (!$isTopup) {
            return;
        }


        //get the user wallet for the transaction
        $userWallet = $model->wallet;
        //calculate the new balance
        $walletTopupPercentage = (float) setting('walletTopupPercentage', 100);
        if (empty($walletTopupPercentage)) {
            $walletTopupPercentage = 100;
        }
        $creditAmount = $model->amount;
        $topupAmount = ($walletTopupPercentage / 100) * $creditAmount;
        $chargedAmount = $creditAmount - $topupAmount;
        //
        $userWallet->balance -= $chargedAmount;
        $userWallet->save();

        //create a new quite wallet transaction
        $walletTransaction = new WalletTransaction();
        $walletTransaction->ref = "charge_" . \Str::random(6);
        $walletTransaction->amount = $chargedAmount;
        $walletTransaction->reason = "Wallet Topup Charges";
        $walletTransaction->wallet_id = $userWallet->id;
        $walletTransaction->is_credit = false;
        $walletTransaction->status = 'successful';
        $walletTransaction->saveQuietly();
    }
}
