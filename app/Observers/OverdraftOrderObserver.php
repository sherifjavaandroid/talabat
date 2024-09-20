<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OutstandingBalance;
use App\Models\Wallet;
use App\Services\AppLangService;

class OverdraftOrderObserver
{


    public function creating(Order $model)
    {
        //if
        $checkWalletBalance = (bool) setting('finance.allowWallet', true);
        //
        if ($checkWalletBalance) {
            //check for outstanding wallet balanace to prevent order
            $wallet = Wallet::firstOrCreate(
                ['user_id' =>  $model->user_id],
                ['balance' => 0.00]
            );

            //user is owing
            if ($wallet->balance < 0) {
                throw new \Exception(__("Order can not be completed has account has outstanding balance. Please topup wallet to clear outstanding balance. Please contact support if you require more info. Thank you"), 1);
            }
        }
    }


    public function updating(Order $model)
    {

        AppLangService::tempLocale();
        //return if order payment method is null
        if ($model->payment_method == null) {
            return;
        }
        //checking is order is completed
        $paymentMethodSlug = $model->payment_method->slug;
        $isNotCashOrder = $paymentMethodSlug != "cash";
        $isTaxiOrder = $model->taxi_order != null;
        //
        if ($isTaxiOrder && $isNotCashOrder && $model->isDirty('total') && in_array($model->status, ['completed', 'delivered', 'success'])) {
            $paid = $model->getOriginal('total');
            $balance = $model->total - $paid;

            try {
                \DB::beginTransaction();

                //if the new total is more than previous total
                if ($balance > 0) {

                    //save into outstanding and debit user wallet
                    $outstandingBalance = OutstandingBalance::firstOrCreate(
                        [
                            'user_id' =>  $model->user_id,
                            'order_id' => $model->id,
                        ],
                        [
                            'amount' => $model->total,
                            'paid' => $paid,
                            'balance' => $balance,
                        ]
                    );

                    $wallet = Wallet::firstOrCreate(
                        ['user_id' =>  $model->user_id],
                        ['balance' => 0.00]
                    );
                    $wallet->balance = $wallet->balance - $balance;
                    $wallet->save();
                    //save transaction
                    $wallet->saveWalletTransaction(
                        $balance,
                        "Order Outstanding Balance",
                        $isCredit = false,
                        $ref = "orod_" . \Str::random(8),
                        $status = "successful",
                    );
                }
                //if the new total is less than previous total, then credit customer wallet
                else {
                    //convert to positive number
                    $balance = abs($balance);
                    $wallet = Wallet::firstOrCreate(
                        ['user_id' =>  $model->user_id],
                        ['balance' => 0.00]
                    );
                    $wallet->balance += $balance;
                    $wallet->save();
                    //save transaction
                    $wallet->saveWalletTransaction(
                        $balance,
                        "Order Balance Overpay refund",
                        $isCredit = true,
                        $ref = "orbr_" . \Str::random(8),
                        $status = "successful",
                    );
                }
                \DB::commit();
            } catch (\Exception $ex) {
                \DB::rollback();
                throw $ex;
            }
        }
        AppLangService::restoreLocale();
    }
}
