<?php

namespace App\Observers;

use App\Models\SubscriptionVendor;
use App\Models\Vendor;
use App\Services\AppLangService;
use Carbon\Carbon;

class SubscriptionObserver
{

    public function updating(SubscriptionVendor $model)
    {
        AppLangService::tempLocale();
        if ($model->isDirty('status') && $model->status == "successful") {
            $model->expires_at = Carbon::now()->addDays($model->subscription->days);
            //update vendor is_active
            $vendor = Vendor::find($model->vendor_id);
            $vendor->is_active = true;
            $vendor->save();
        }
        AppLangService::restoreLocale();
    }
}
