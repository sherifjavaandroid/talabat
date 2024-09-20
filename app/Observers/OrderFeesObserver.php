<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Fee;
use App\Models\FeeVendor;

class OrderFeesObserver
{

   

    public function creating(Order $model)
    {
        //calculate vendor fees
        if (empty($model->fees)) {
            $this->calculateOrderTotalFees($model);
        }
    }


    public function updating(Order $model)
    {
        //calculate vendor fees
        if (empty($model->fees)) {
            $this->calculateOrderTotalFees($model);
        }
    }



    function calculateOrderTotalFees($order)
    {
        //get vendor fees
        $totalOrderFees = 0;
        $orderFees = [];
        $vendorFees = FeeVendor::whereVendorId($order->vendor_id)->get();
        foreach ($vendorFees as $vendorFee) {
            $fee = Fee::active()->whereId($vendorFee->fee_id)->first();
            if (!empty($fee)) {
                if ($fee->percentage) {
                    $calFee = ($fee->value / 100) * $order->sub_total;
                } else {
                    $calFee = $fee->value;
                }

                //
                $totalOrderFees += $calFee;
                array_push($orderFees, [
                    "id" => $fee->id,
                    "name" => $fee->name,
                    "amount" => $calFee,
                ]);
            }
        }

        //
        $order->fees = json_encode($orderFees);
        $order->total += $totalOrderFees;
    }
}
