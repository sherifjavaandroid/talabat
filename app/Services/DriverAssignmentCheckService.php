<?php

namespace App\Services;


use App\Models\User;

class DriverAssignmentCheckService
{
    public function __constuct()
    {
        //
    }


    public function checkCanAssignOrder($order)
    {

        if (empty(request()->driver_id)) {
            return;
        }
        $driver = User::find(request()->driver_id);
        if (empty($driver->commission)) {
            $driver->commission =  setting('driversCommission', "0");
        }

        $enableDriverWallet = (bool) setting('enableDriverWallet', "0");
        $driverWalletRequiredForTotal = (bool) setting('driverWalletRequiredForTotal', 1);
        $adminDriverFeeCommission = 0;
        $driverWalletRequired = (bool) setting('driverWalletRequired', "0");

        ///calculate admin driver commission
        if (!empty($order->taxi_order)) {
            $adminDriverFeeCommission = ((100 - $driver->commission) / 100) * $order->total;
        } else {
            $adminDriverFeeCommission = (((100 - $driver->commission) / 100) * $order->delivery_fee);
        }


        //wallet system
        if (empty($order->driver_id) && !empty(request()->driver_id) && $enableDriverWallet) {

            //
            $driverWallet = $driver->wallet;
            if (empty($driverWallet)) {
                $driverWallet = $driver->updateWallet(0);
            }

            //allow if wallet has enough balance
            if ($driverWalletRequired) {
                if ($driverWalletRequiredForTotal && ($order->total > $driverWallet->balance)) {
                    throw new \Exception(__("Insufficient wallet balance, Wallet balance is less than order total amount"), 1);
                } else if (!$driverWalletRequiredForTotal && ($adminDriverFeeCommission > $driverWallet->balance)) {
                    throw new \Exception(__("Order not assigned. Insufficient wallet balance"), 1);
                }
            }
            //  else if ($order->payment_method->slug == "cash") {
            //     if ($driverWalletRequiredForTotal && ($order->total > $driverWallet->balance)) {
            //         return response()->json([
            //             "message" => __("Insufficient wallet balance, Wallet balance is less than order total amount")
            //         ], 400);
            //     } else {
            //     }
            // } else if ($order->payment_method->slug != "cash" && $order->delivery_fee > $driverWallet->balance) {
            //     return response()->json([
            //         "message" => __("Insufficient wallet balance, Wallet balance is less than order delivery fee")
            //     ], 400);
            // }
        }
    }
}
