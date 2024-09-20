<?php

namespace App\Observers;

use App\Models\OutstandingBalance;
use App\Models\Wallet;

class OverdraftWalletObserver
{


    public function created(Wallet $model)
    {
    }


    public function updated(Wallet $model)
    {
        if ($model->isDirty('balance')) {
            $addedAmount =  $model->balance - $model->getOriginal('balance');
            $outstandingBalances = OutstandingBalance::where('user_id', $model->user_id)
                ->where('completed', 0)
                ->get();

            //loop through and mark paid orders 
            foreach ($outstandingBalances as $key => $outstandingBalance) {
                if ($addedAmount >= $outstandingBalance->balance) {
                    //clear pending balance and update paid
                    $outstandingBalance->balance = 0;
                    $outstandingBalance->paid += $outstandingBalance->balance;
                    $outstandingBalance->completed = true;
                    $outstandingBalance->save();
                    //
                    $addedAmount -= $outstandingBalance->balance;
                } else if ($addedAmount > 0) {
                    //
                    $outstandingBalance->balance -= $addedAmount;
                    $outstandingBalance->paid += $addedAmount;
                    $outstandingBalance->save();
                    //
                    $addedAmount = 0;
                }
            }
        }
    }
}
