<?php

namespace App\Observers;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyPointReport;
use App\Models\Order;
use App\Services\AppLangService;

class OrderLoyaltyObserver
{

    public function updated(Order $order)
    {
        AppLangService::tempLocale();
        //check if order is completed
        if (
            in_array($order->status, ['success', 'successful', 'completed', 'delivered']) &&
            in_array($order->payment_status, ['successful', 'completed'])
        ) {
            //check if loyalty is enabled
            $enableLoyalty = (bool) setting('finance.enableLoyalty', false);
            if ($enableLoyalty) {
                //check if the points has been awarded already
                $loyaltyReport = LoyaltyPointReport::whereOrderId($order->id)->first();
                if (empty($loyaltyReport) || !isset($loyaltyReport)) {

                    try {

                        \DB::beginTransaction();
                        //first or create the user loyaltypoint
                        $loyaltyPoint = LoyaltyPoint::firstOrCreate([
                            "user_id" => $order->user_id,
                        ], [
                            "points" => 0
                        ]);
                        //generate point base on the order total
                        $points = setting('finance.amount_to_point', 0.001) * $order->total;
                        $loyaltyPoint->points += $points;
                        $loyaltyPoint->save();
                        //record the report
                        $loyaltyReport = new LoyaltyPointReport();
                        $loyaltyReport->order_id = $order->id;
                        $loyaltyReport->points = $points;
                        $loyaltyReport->loyalty_point_id = $loyaltyPoint->id;
                        $loyaltyReport->is_credit = true;
                        $loyaltyReport->save();
                        \DB::commit();
                    } catch (\Exception $ex) {
                        \DB::rollback();
                        logger("Error applying loyalty Point", [$ex]);
                    }
                }
            }
        }
        //
        AppLangService::restoreLocale();
    }
}
