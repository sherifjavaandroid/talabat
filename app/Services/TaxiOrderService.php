<?php

namespace App\Services;

use App\Models\Order;
use App\Traits\FirebaseAuthTrait;
use App\Traits\GoogleMapApiTrait;
use MrShan0\PHPFirestore\Fields\FirestoreObject;

class TaxiOrderService
{
    use FirebaseAuthTrait, GoogleMapApiTrait;

    public function __constuct()
    {
        //
    }


    public function saveTaxiOrderToFirebaseFirestore(Order $order)
    {

        $firestoreClient = $this->getFirebaseStoreClient();
        $pickupLat = "" . $order->taxi_order->pickup_latitude . "," . $order->taxi_order->pickup_longitude;
        $dropoffLat = "" . $order->taxi_order->dropoff_latitude . "," . $order->taxi_order->dropoff_longitude;

        //
        try {

            $orderRef = "orders/" . $order->code . "";
            if ($order->status == "pending") {
                $orderRef = "newTaxiOrders/" . $order->code . "";
            }

            $firestoreClient->addDocument($orderRef, [
                'id' => $order->id,
                'code' => "" . $order->code . "",
                'driver_id' => $order->driver_id,
                'total' => $order->total,
                'amount' => $order->total,
                'vehicle_type_id' => $order->taxi_order->vehicle_type_id ?? "",
                'status' => $order->status,
                'earth_distance' => $this->getEarthDistance($order->taxi_order->pickup_latitude, $order->taxi_order->pickup_longitude),
                'trip_distance' => $this->getRelativeDistance($pickupLat, $dropoffLat),
                'range' => (int) driverSearchRadius($order),
                'notify' => (int) setting('maxDriverOrderNotificationAtOnce', 1),
                'pickup' => new FirestoreObject([
                    'address' => $order->taxi_order->pickup_address,
                    'lat' => $order->taxi_order->pickup_latitude,
                    'lng' => $order->taxi_order->pickup_longitude
                ]),
                'dropoff' => new FirestoreObject([
                    'address' => $order->taxi_order->dropoff_address,
                    'lat' => $order->taxi_order->dropoff_latitude,
                    'lng' => $order->taxi_order->dropoff_longitude
                ]),
                "ignoredDrivers" => [],
                "notificationTime" => setting('alertDuration', 15),
            ]);
        } catch (\Exception $error) {
            logger("New Docus error", [$error]);
        }
    }

    public function updateTaxiOrderToFirebaseFirestore(Order $order)
    {
        $firestoreClient = $this->getFirebaseStoreClient();
        $pickupLat = "" . $order->taxi_order->pickup_latitude . "," . $order->taxi_order->pickup_longitude;
        $dropoffLat = "" . $order->taxi_order->dropoff_latitude . "," . $order->taxi_order->dropoff_longitude;

        try {

            //
            $orderRef = "orders/" . $order->code . "";
            if ($order->status == "pending") {
                $orderRef = "newTaxiOrders/" . $order->code . "";
            }

            //update firebase document
            $firestoreClient->updateDocument($orderRef, [
                'id' => $order->id,
                'code' => "" . $order->code . "",
                'vehicle_type_id' => $order->taxi_order->vehicle_type_id ?? "",
                'driver_id' => $order->driver_id,
                'total' => $order->total,
                'amount' => $order->total,
                'status' => $order->status,
                'earth_distance' => $this->getEarthDistance($order->taxi_order->pickup_latitude, $order->taxi_order->pickup_longitude),
                'trip_distance' => $this->getRelativeDistance($pickupLat, $dropoffLat),
                'range' => (int) driverSearchRadius($order),
                'notify' => (int) setting('maxDriverOrderNotificationAtOnce', 1),
                'pickup' => new FirestoreObject([
                    'address' => $order->taxi_order->pickup_address,
                    'lat' => $order->taxi_order->pickup_latitude,
                    'lng' => $order->taxi_order->pickup_longitude
                ]),
                'dropoff' => new FirestoreObject([
                    'address' => $order->taxi_order->dropoff_address,
                    'lat' => $order->taxi_order->dropoff_latitude,
                    'lng' => $order->taxi_order->dropoff_longitude
                ]),
                "ignoredDrivers" => [],
                "notificationTime" => setting('alertDuration', 15),
            ]);

            //delete from firebase
            if (in_array($order->status, ['failed', 'cancelled', 'delivered'])) {
                $firestoreClient->deleteDocument("orders/" . $order->code . "");
            }
            //delete from newTaxiOrders
            if (!in_array($order->status, ['pending', 'preparing'])) {
                $firestoreClient->deleteDocument("newTaxiOrders/" . $order->code . "");
            }
        } catch (\Exception $error) {
            logger("Update Docus error", [$error]);
        }
    }


    public function updateFailedPayment(Order $order)
    {
        //
        $firestoreClient = $this->getFirebaseStoreClient();
        //update firebase document
        $firestoreClient->updateDocument("orders/" . $order->code . "", [
            'status' => "failed",
        ]);

        //delete from firebase
        if (in_array($order->status, ['failed', 'cancelled', 'delivered'])) {
            $firestoreClient->deleteDocument("orders/" . $order->code . "");
        }

        //
        $order->payment_status = "failed";
        $order->setStatus("failed");
        $order->save();
    }
}
