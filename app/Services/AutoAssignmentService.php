<?php

namespace App\Services;

use App\Traits\FirebaseMessagingTrait;
use App\Traits\FirebaseAuthTrait;
use App\Traits\GoogleMapApiTrait;
use MrShan0\PHPFirestore\Fields\FirestoreObject;

class AutoAssignmentService
{
    use FirebaseMessagingTrait;
    use FirebaseAuthTrait, GoogleMapApiTrait;

    public function __constuct()
    {
        //
    }

    public function sendNewOrderNotification($driver, $newOrderData, $address, $distance)
    {

        // logger("sendNewOrderNotification ==>", [$newOrderData]);
        $this->loadLocale();
        $driverTopic = "d_" . $driver->id . "";
        $title = __("New Order Alert");
        $body = __("Pickup Location") . ": " . $address . " (" . $distance . "km)";
        $newOrderData["title"] = $title;
        $newOrderData["body"] = $body;
        //push to firebase node

        $this->sendFirebaseNotification(
            $driverTopic,
            $title,
            $body,
            $newOrderData,
            $onlyData = true,
            "new_order_channel",
            $noSound = false
        );
        $this->resetLocale();
    }

    public function sendFailedNewOrderNotification($driver, $autoAssignment)
    {
        $this->loadLocale();
        $driverTopic = "d_" . $driver->id . "";
        $title = "#" . $autoAssignment->order->code . " " . __("Order Alert(Released)");
        $body = __("This order has not receive update from you and its there be released for other driver to accept");
        $notificationData = [
            "title" => $title,
            "body" => $body,
        ];
        $this->sendFirebaseNotification(
            $driverTopic,
            $title,
            $body,
            $notificationData,
            $onlyData = true,
            "new_order_channel",
            $noSound = false
        );
        $this->resetLocale();
    }




    // firebase
    public function saveNewOrderToFirebaseFirestore($driver, $newOrderData, $address = null, $distance = null)
    {


        //
        try {

            $firestoreClient = $this->getFirebaseStoreClient();
            $orderRef = "driver_new_order/" . $driver->id . "";
            try {
                $firestoreClient->addDocument($orderRef, $newOrderData);
            } catch (\Exception $error) {
                $firestoreClient->updateDocument($orderRef, $newOrderData);
            }
        } catch (\Exception $error) {
            logger("New Docus error", [$error]);
        }
    }

    public function deleteNewOrderToFirebaseFirestore($driver)
    {

        $firestoreClient = $this->getFirebaseStoreClient();

        //
        try {

            $orderRef = "driver_new_order/" . $driver->id . "";
            $firestoreClient->deleteDocument($orderRef);
        } catch (\MrShan0\PHPFirestore\Exceptions\Client\NotFound $error) {
            logger("Collection not found");
        } catch (\Exception $error) {
            logger("New Docus error", [$error]);
        }
    }
}
