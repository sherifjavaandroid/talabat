<?php

namespace App\Traits;

use Exception;
use App\Models\User;
use App\Models\Referral;

trait ReferralTrait
{

    //TODO: Handle the referral process
    //
    public function handleControllerReferralRegistration($referralCode, $user)
    {
        $enableReferSystem = (bool) setting('enableReferSystem', "0");
        $enableOnRegistrationReferReward = (bool) setting('enableOnRegistrationReferReward', 0);
        $referRewardAmount = (float) setting('referRewardAmount', "0");
        if ($enableReferSystem && !empty($referralCode)) {
            $referringUser = User::where('code', $referralCode)->first();
            if (!empty($referringUser)) {
                //create the referall record
                $referral = new Referral();
                $referral->user_id = $referringUser->id;
                $referral->referred_user_id = $user->id;
                $referral->amount = $referRewardAmount;
                //topup the referring user wallet and confirm the referral if the setting is enabled
                if ($enableOnRegistrationReferReward) {
                    $referringUser->topupWallet($referRewardAmount);
                    $referral->confirmed = true;
                }
                $referral->save();
            } else {
                throw new Exception(__("Invalid referral code"), 1);
            }
        }
    }


    //
    public function handlePartnerControllerReferral($referralCode, $user)
    {
        $enableReferSystem = (bool) setting('enableReferSystem', "0");
        $enableOnRegistrationReferReward = (bool) setting('enableOnRegistrationReferReward', 0);
        $referRewardAmount = (float) setting('referRewardAmount', "0");
        if ($enableReferSystem && !empty($referralCode)) {
            //
            $referringUser = User::where('code', $referralCode)->first();
            if (!empty($referringUser)) {
                //create the referall record
                $referral = new Referral();
                $referral->user_id = $referringUser->id;
                $referral->referred_user_id = $user->id;
                $referral->amount = $referRewardAmount;
                //topup the referring user wallet and confirm the referral if the setting is enabled
                if ($enableOnRegistrationReferReward) {
                    $referringUser->topupWallet($referRewardAmount);
                    $referral->confirmed = true;
                }
                $referral->save();
            } else {
                throw new Exception(__("Invalid referral code"), 1);
            }
        }
    }

    //
    public function handleDriverRegLivewireReferral($referralCode, $user)
    {
        $enableReferSystem = (bool) setting('enableReferSystem', "0");
        $enableOnRegistrationReferReward = (bool) setting('enableOnRegistrationReferReward', 0);
        $referRewardAmount = (float) setting('referRewardAmount', "0");
        if ($enableReferSystem && !empty($referralCode)) {
            //
            $referringUser = User::where('code', $referralCode)->first();
            if (!empty($referringUser)) {
                //create the referall record
                $referral = new Referral();
                $referral->user_id = $referringUser->id;
                $referral->referred_user_id = $user->id;
                $referral->amount = $referRewardAmount;
                //topup the referring user wallet and confirm the referral if the setting is enabled
                if ($enableOnRegistrationReferReward) {
                    $referringUser->topupWallet($referRewardAmount);
                    $referral->confirmed = true;
                }
                $referral->save();
            } else {
                throw new Exception(__("Invalid referral code"), 1);
            }
        }
    }
}
