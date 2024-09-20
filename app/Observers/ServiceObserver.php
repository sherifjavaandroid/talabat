<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\Vendor;
use App\Models\SubscriptionVendor;

class ServiceObserver
{

    public function creating(Service $model)
    {
        //location is null
        if ($model->location == null) {
            $model->location = false;
        }

        //if is_active is null
        if ($model->is_active == null) {
            $model->is_active = false;
        }

        //check if vendor uses subscription
        $vendor = Vendor::find($model->vendor_id);
        //
        if (!empty($vendor) && $vendor->use_subscription) {

            //get vendor subscription
            if ($vendor->has_subscription) {
                $vendorSubscription = SubscriptionVendor::active()->with('subscription')->where('vendor_id', $vendor->id)->first();
                //check if the subscription have qty set
                if (!empty($vendorSubscription->subscription->qty)) {
                    //
                    $totalProducts = Service::where('vendor_id', $vendor->id)->count();
                    //if products that vendor has is more or equals to qty
                    if ($vendorSubscription->subscription->qty <= $totalProducts) {
                        throw new \Exception(__("Vendor reached maximum allow items for current subscription"), 1);
                    }
                } else {
                    //qty not set, so continue
                }
            } else {
                throw new \Exception(__("Vendor requires subscription"), 1);
            }
        }
    }
}
