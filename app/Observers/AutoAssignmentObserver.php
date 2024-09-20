<?php

namespace App\Observers;

use App\Models\AutoAssignment;
use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseDBTrait;
use App\Traits\OrderFCMTrait;
use App\Traits\OrderTrait;

class AutoAssignmentObserver
{

    use FirebaseDBTrait, FirebaseAuthTrait;
    use OrderTrait, OrderFCMTrait;



    public function updated(AutoAssignment $model)
    {
        $this->clearDriverNewOrderFCM($model->driver_id);
        //queue deletiong in driver search seconds 
        \Artisan::call('order:auto_assignment_cancel');
    }

    public function deleted(AutoAssignment $model)
    {
        $this->clearDriverNewOrderFCM($model->driver_id);
        //queue deletiong in driver search seconds 
        \Artisan::call('order:auto_assignment_cancel');
    }


    function clearDriverNewOrderFCM($driverId)
    {
        $firestoreClient = $this->getFirebaseStoreClient();

        $driverNewOrderRef = "driver_new_order/{$driverId}";
        try {
            $firestoreClient->deleteDocument($driverNewOrderRef);
        } catch (\Exception $error) {
            logger("Error deleting driver new order FCM", [$error]);
        }

    }
}
