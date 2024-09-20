<?php

namespace App\Traits;

use App\Models\Order;
use App\Services\FirestoreRestService;
use App\Services\JobHandlerService;

trait OrderFCMTrait
{
    use GoogleMapApiTrait;
    use FirebaseAuthTrait;


    //
    public function pushOrderToFCM(Order $order)
    {
        //push to firebase
        (new JobHandlerService())->pushOrderToFCMJob($order);
    }



    // DATA
    public function clearFirestore(Order $order)
    {
        //clear firebase data
        (new JobHandlerService())->clearFCMJob($order);
    }

    public function clearDriverNewOrderFirestore()
    {

        //
        try {
            $firestoreRestService = new FirestoreRestService();
            $expiredDriverNewOrders = $firestoreRestService->exipredDriverNewOrders();
            foreach ($expiredDriverNewOrders as $expiredDriverNewOrder) {
                try {
                    (new JobHandlerService())->clearDriverFCMJob($expiredDriverNewOrder);
                } catch (\Exception $ex) {
                    logger("Error deleting driver new order alert firestore document", [$ex->getMessage() ?? $ex]);
                }
            }
        } catch (\Exception $ex) {
            logger("Error deleting fdriver new order irebase firestore document", [$ex->getMessage() ?? $ex]);
        }
    }
}
