<?php

namespace App\Traits;

use App\Jobs\RegularOrderMatchingJob;
use App\Models\Order;

trait OrderJobTrait
{

    public function handleRegularAssignmentJob(Order $order)
    {
        $useFCMJob = (bool) setting('useFCMJob', "0");
        if ($useFCMJob && $order->vendor != null) {
            //
            if (delayFCMJob()) {
                RegularOrderMatchingJob::dispatch($order)->delay(now()->addSeconds(jobDelaySeconds()));
            } else {
                (new RegularOrderMatchingJob($order))->handle();
            }
        }
    }


    public function canCalledMatchingJob(Order $order)
    {
        //
        $vendorHasAutoAssign = $order->Vendor->auto_assignment ?? 0;
        $noAutoAssignmentPending = $order->auto_assignment == null;
        $vendorTypeSlug = $order->vendor->vendor_type->slug ?? "";
        $slugAllowed = !in_array($vendorTypeSlug, ["booking", "service"]);
        $autoAsignmentStatus = setting('autoassignment_status', "ready");
        $autoAsignmentStatus = is_array($autoAsignmentStatus) ? $autoAsignmentStatus : [$autoAsignmentStatus];
        $noDriver = $order->driver_id == null;
        $hasDeliveryPlace = $order->delivery_address_id != null || $order->stops != null;
        $allowedStatus = in_array($order->status, $autoAsignmentStatus);

        //run the job when creteria is meet
        if ($allowedStatus && $vendorHasAutoAssign && $slugAllowed && $noAutoAssignmentPending && $hasDeliveryPlace && $noDriver) {
            return true;
        } else {
            return false;
        }
    }
}
