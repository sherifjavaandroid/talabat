<?php

namespace App\Observers;

use App\Models\Payout;
use App\Services\AppLangService;

class PayoutObserver
{

    public function created(Payout $model)
    {
        AppLangService::tempLocale();
        if ($model->earning->amount < $model->amount) {
            throw new \Exception(__("Payout amount more than current earning balance"), 1);
        }
        //debit the user earning
        $model->earning->amount -= $model->amount;
        $model->earning->save();
        AppLangService::restoreLocale();
    }

    public function updated(Payout $model)
    {
        AppLangService::tempLocale();
        //refund earning amount if payout is rejected
        $failedArray = ['failed', 'cancelled', 'rejected'];
        if ($model->isDirty('status') && in_array($model->status, $failedArray)) {
            $model->earning->amount += $model->amount;
            $model->earning->save();
        }
        AppLangService::restoreLocale();
    }
}
