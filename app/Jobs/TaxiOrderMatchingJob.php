<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseMessagingTrait;
use AnthonyMartin\GeoLocation\GeoPoint;
use App\Traits\FirebaseDBTrait;
use App\Traits\GoogleMapApiTrait;
use Carbon\Carbon;

class TaxiOrderMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait, FirebaseMessagingTrait, FirebaseDBTrait;
    use GoogleMapApiTrait;


    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // logger("Taxi order matching");

        // logger("Order loaded ==> " . $this->order->code . "");
        //
        $order = $this->order;
        $order->refresh();
        //check if driver as been assinged to order now
        if (!empty($order->driver_id) || in_array($order->status, ["cancelled", "delivered", "failed", "scheduled"])) {
            // logger("Driver has been assigned. Now closing matching for order ==> {$order->code}");
            return;
        }

        //to ensure unpaid online order is not sent to drivers
        if ($order->can_auto_assign_driver) {

            try {
                $pickupLocationLat = $order->taxi_order->pickup_latitude;
                $pickupLocationLng = $order->taxi_order->pickup_longitude;
                //
                $pickupLat = "" . $order->taxi_order->pickup_latitude . "," . $order->taxi_order->pickup_longitude;
                $dropoffLat = "" . $order->taxi_order->dropoff_latitude . "," . $order->taxi_order->dropoff_longitude;


                //add the new order to it
                $driverSearchRadius = driverSearchRadius($order);
                //pickup data
                $pickup = [
                    'lat' => $pickupLocationLat,
                    'lng' => $pickupLocationLng,
                    'address' => $order->taxi_order->pickup_address,
                ];


                //dropoff data
                $dropoffLocationLat = $order->taxi_order->dropoff_latitude;
                $dropoffLocationLng = $order->taxi_order->dropoff_longitude;
                $dropoff = [
                    'lat' => $dropoffLocationLat,
                    'lng' => $dropoffLocationLng,
                    'address' => $order->taxi_order->dropoff_address,
                ];



                //get when the order can expire
                $newTimestamp = $this->getExpireTimestamp($order);
                //
                $newOrderData = [
                    "dropoff" => json_encode($dropoff),
                    "pickup" => json_encode($pickup),
                    'amount' => (string)$order->total,
                    'total' => (string)$order->total,
                    'id' => (string)$order->id,
                    'range' => (string) $driverSearchRadius,
                    'status' => (string)$order->status,
                    'trip_distance' => $this->getRelativeDistance($pickupLat, $dropoffLat),
                    'code' => $order->code,
                    'vehicle_type_id' => $order->taxi_order->vehicle_type_id,
                    'earth_distance' => $this->getEarthDistance(
                        $order->taxi_order->pickup_latitude,
                        $order->taxi_order->pickup_longitude,
                    ),
                    'exipres_at' => $newTimestamp,
                    'exipres_at_timestamp' => Carbon::createFromTimestamp($newTimestamp)->toDateTimeString(),
                    "notifiable" => (int) setting('maxDriverOrderNotificationAtOnce', 1),
                    'informed' => [],
                    'ignored' => [],

                ];
                //push to firebase
                $path = "searchingTaxiOrders/" . $order->code . "";
                $this->pushToFirestore($newOrderData, $path);
            } catch (\Exception $ex) {
                logger("Skipping Order", [$order->id]);
                logger("Order Error", [$ex->getMessage() ?? '']);
            }
        }

        //queue another check to resend order incase no driver accepted the order
        // logger("queue another check to resend order incase no driver accepted the order");
        $alertDuration = ((int) setting('alertDuration', 15)) + 10;
        TaxiOrderMatchingJob::dispatch($order)->delay(now()->addSeconds($alertDuration));
    }


    //
    public function getDistance($loc1, $loc2)
    {
        $geopointA = new GeoPoint($loc1[0], $loc1[1]);
        $geopointB = new GeoPoint($loc2[0], $loc2[1]);
        return $geopointA->distanceTo($geopointB, 'kilometers');
    }

    public function getExpireTimestamp($order)
    {
        $currentTimeStamp = Carbon::now()->timestamp;
        $nextTimestamp = $currentTimeStamp + (setting('alertDuration', 15) * 1000);
        return $nextTimestamp;
    }
}
