<?php

namespace App\Observers;

use App\Models\TaxiOrder;
use App\Services\JobHandlerService;

class TaxiOrderTripObserver
{

    
    public function created(TaxiOrder $model)
    {
        //call the service to handle the matching
        (new JobHandlerService())->uploadTaxiOrderJob($model->order);
    }

    public function updated(TaxiOrder $model)
    {
    }


}
