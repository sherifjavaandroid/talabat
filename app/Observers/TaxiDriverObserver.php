<?php

namespace App\Observers;

use App\Models\User;
use App\Services\JobHandlerService;
use App\Traits\FirebaseAuthTrait;

class TaxiDriverObserver
{

    use FirebaseAuthTrait;

    public function updated(User $user)
    {

        //drver update related
        if (!$user->hasRole('driver')) {
            return;
        }

        //update driver online record on firebase
        if ($user->isDirty('is_online')) {
            //update driver node on firebase 
            (new JobHandlerService())->driverDetailsJob($user);
        }
    }
}
