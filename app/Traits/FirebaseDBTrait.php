<?php

namespace App\Traits;


trait FirebaseDBTrait
{
    use FirebaseAuthTrait;

    public function pushToFirestore($payload, $path)
    {
        $firestoreClient = $this->getFirebaseStoreClient();
        //check if the node already exsits on firebase firestore
        try {
            $firestoreClient->updateDocument($path, $payload);
        } catch (\Exception $ex) {
            logger("Error updating firestore document", [$ex->getMessage() ?? $ex]);
            try {
                $firestoreClient->addDocument($path, $payload);
            } catch (\Exception $ex) {
                logger("Error creating firestore document", [$ex->getMessage() ?? $ex]);
            }
        }
    }

    public function deleteFirestoreOrderNode($order)
    {
        $firestoreClient = $this->getFirebaseStoreClient();
        //check if the node already exsits on firebase firestore
        try {
            $path = "searchingOrders/";
            if(!empty($order->taxi_order)){
                $path = "searchingTaxiOrders/";
            }
            $path .= "{$order->code}";
            $firestoreClient->deleteDocument($path);
        } catch (\Exception $ex) {
            logger("Error deleting firestore document", [$ex->getMessage() ?? $ex]);
        }
    }
}
