<?php

namespace App\Observers;

use App\Models\Vendor;
use App\Models\VendorOpenStatus;

class VendorOpenObserver
{


    public function updated(Vendor $vendor)
    {

        if ($vendor->isDirty('is_open')) {

            //if vendor has days
            if (empty($vendor->days) || $vendor->days->count() <= 0) {
                return;
            }
            //
            $vendorOpenStatus = VendorOpenStatus::firstOrCreate(
                ['vendor_id' => $vendor->id],
                [
                    'is_open' => $vendor->is_open,
                    'auto_set' => true
                ]
            );

            //
            $vendorOpenStatus->is_open = $vendor->is_open;
            $vendorOpenStatus->auto_set = !$vendorOpenStatus->auto_set;
            $vendorOpenStatus->save();
        }
    }
}