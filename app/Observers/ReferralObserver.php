<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Referral;
use App\Services\AppLangService;

class ReferralObserver
{



    public function updated(Order $model)
    {
        AppLangService::tempLocale();
        //check if the order is completed
        if (in_array($model->status, ['completed', 'delivered', 'successful'])) {
            //find a pending/unconfirm referral for this order owner
            $referral = Referral::where('referred_user_id', $model->user_id)->where('confirmed', 0)->first();
            if (!empty($referral)) {
                //add the amount to the user(in this case the person who referred this user)
                try {
                    \DB::beginTransaction();
                    $referral->referringUser->topupWallet($referral->amount);
                    $referral->confirmed = true;
                    $referral->save();
                    \DB::commit();
                } catch (\Exception $error) {
                    \DB::rollback();
                    logger("Issue with fufilling reward", [$error]);
                }
            }
        }
        AppLangService::restoreLocale();
    }
}
