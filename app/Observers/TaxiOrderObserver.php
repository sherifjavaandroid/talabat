<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\AppLangService;
use App\Services\JobHandlerService;
use App\Traits\FirebaseAuthTrait;
use App\Traits\OrderTrait;
use App\Traits\OrderFCMTrait;
use App\Traits\TaxiTrait;

class TaxiOrderObserver
{

    use FirebaseAuthTrait, OrderTrait;
    use OrderFCMTrait, TaxiTrait;

    public function updating(Order $model)
    {
        AppLangService::tempLocale();
        //recalculate taxi order amount
        if (!empty(request()->status) && !empty($model->taxi_order) && in_array(request()->status, ["delivered", "completed", "success"])) {
            //
            $newTaxiFare = $this->getRecalculatedTaxiOrderTotalPrice($model);
            $model->sub_total = $newTaxiFare;
            $model->total = ($newTaxiFare - $model->discount) + $model->tip;
            //update the taxi order
            $taxiOrder = $this->getRecalculatedTaxiOrderBreakdown($model);
            $taxiOrder->saveQuietly();
        }
        AppLangService::restoreLocale();
    }

    public function updated(Order $model)
    {

        $driver = $model->driver;
        //update driver node on firebase
        if (!empty($driver)) {
            (new JobHandlerService())->driverDetailsJob($driver);
        }

        //
        $this->clearFirestore($model);
    }
}
