<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\NewAccountMail;
use App\Services\JobHandlerService;
use App\Services\MailHandlerService;
use App\Traits\FirebaseAuthTrait;

class UserObserver
{

    use FirebaseAuthTrait;

    public function creating(User $user)
    {
        //
        $user->code = \Str::random(3) . "" . $user->id . "" . \Str::random(2);
    }

    public function created(User $user)
    {
        //update wallet
        if (empty($user->wallet)) {
            $user->createWallet(0);
        }
        //send mail
        try {
            // \Mail::to($user->email)->send(new NewAccountMail($user));
            MailHandlerService::sendMail(new NewAccountMail($user), $user->email);
        } catch (\Exception $ex) {
            // logger("Mail Error", [$ex]);
            logger("Mail Error: please check your mail server settings");
        }

        //set vehicle type id, if any to firebase
        $this->updateDriverVehicleType($user);
        //enforce user with client role, this will be overrite later if role is assigned again
        if (empty($user->roles())) {
            $user->assignRole("client");
        }
    }

    public function updating(User $user)
    {
        $isAdmin = \Auth::user()->hasAnyRole('admin');
        //check if profile update is disabled
        if ((bool) setting('enableProfileUpdate', 1) == false && !$isAdmin) {
            $preventColumns = ["name", "email", "phone", "country_code"];
            $dirty = $user->getDirty();
            foreach ($dirty as $key => $value) {
                if (in_array($key, $preventColumns)) {
                    $msg = __("Profile update is disabled");
                    $msg .= ":: $key";
                    throw new \Exception($msg);
                }
            }
        }
    }

    public function updated(User $user)
    {
        //set vehicle type id, if any to firebase
        $this->updateDriverVehicleType($user);
    }

    public function deleting(User $model)
    {
    }



    // UPDATE DRIVER DATA TO FIRESTORE
    public function updateDriverVehicleType(User $user)
    {

        //driver user
        if (!$user->hasRole('driver')) {
            return;
        }

        //
        (new JobHandlerService())->driverVehicleTypeJob($user);
    }
}