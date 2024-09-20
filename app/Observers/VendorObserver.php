<?php

namespace App\Observers;

use App\Models\Vendor;
use App\Services\JobHandlerService;

class VendorObserver
{

    public function created($vendor)
    {
        //notify send them a welcome email
        try {
            (new JobHandlerService())->sendWelcomeToVendor($vendor);
        } catch (\Exception $ex) {
            logger("Mail Error", [$ex]);
        }
    }

    public function updated($vendor)
    {

        if ($vendor->isDirty('is_active')) {
            //notify them of change in thier active status
            (new JobHandlerService())->sendUpdateToVendor($vendor);
        }
    }

    public function deleted($vendor)
    {
        //notify send them a welcome email

    }
}
