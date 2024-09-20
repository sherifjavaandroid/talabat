<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseDBTrait;

class FirestoreOnDeviceOrderAssignment extends Command
{

    use FirebaseAuthTrait, FirebaseDBTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:assign:firestore-on-device';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out the regular orders to driver firestore node';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $autoAsignmentStatus = setting('autoassignment_status', "ready");
        //get orders in ready state
        $orders = Order::currentStatus($autoAsignmentStatus)
            ->whereHas('vendor', function ($query) {
                return $query->where('auto_assignment', 1)
                    ->whereHas('vendor_type', function ($query) {
                        //avoid sending
                        return $query->whereNotIn('slug', ["booking", "service"]);
                    });
            })
            ->doesntHave("auto_assignment")
            ->where(function ($query) {
                $query->whereNotNull('delivery_address_id')
                    ->orWhereHas('stops');
            })
            ->whereNull('driver_id')
            ->limit(20)->get();
        // logger("orders", [$orders->pluck('id')]);

        //
        foreach ($orders as $order) {
            // logger("Order loaded ==> " . $order->code . "");
            //
            try {
                //add the new order to it
                $pickupLocationLat = $order->type != "parcel" ? $order->vendor->latitude : $order->pickup_location->latitude;
                $pickupLocationLng = $order->type != "parcel" ? $order->vendor->longitude : $order->pickup_location->longitude;
                $pickup = [
                    'lat' => $pickupLocationLat,
                    'long' => $pickupLocationLng,
                    'address' => $order->type != "parcel" ? $order->vendor->address : $order->pickup_location->address,
                    'city' => $order->type != "parcel" ? "" : $order->pickup_location->city,
                    'state' => $order->type != "parcel" ? "" : $order->pickup_location->state ?? "",
                    'country' => $order->type != "parcel" ? "" : $order->pickup_location->country ?? "",
                ];


                //dropoff data
                $dropoffLocationLat = $order->type != "parcel" ? $order->delivery_address->latitude : $order->dropoff_location->latitude;
                $dropoffLocationLng = $order->type != "parcel" ? $order->delivery_address->longitude : $order->dropoff_location->longitude;
                $dropoff = [
                    'lat' => $dropoffLocationLat,
                    'long' => $dropoffLocationLng,
                    'address' => $order->type != "parcel" ? $order->delivery_address->address : $order->dropoff_location->address,
                    'city' =>  $order->type != "parcel" ? "" : $order->dropoff_location->city,
                    'state' => $order->type != "parcel" ? "" : $order->pickup_location->state ?? "",
                    'country' => $order->type != "parcel" ? "" : $order->pickup_location->country ?? "",
                ];
                //
                $newOrderData = [
                    "pickup" => json_encode($pickup),
                    "dropoff" => json_encode($dropoff),
                    'amount' => (string)$order->delivery_fee,
                    'total' => (string)$order->total,
                    'vendor_id' => (string)$order->vendor_id,
                    'is_parcel' => (string)($order->type == "parcel"),
                    'package_type' =>  (string)($order->package_type->name ?? ""),
                    'id' => (string)$order->id,
                    'range' => (string)$order->vendor->delivery_range,
                    "notificationTime" => setting('alertDuration', 15),
                    "notifiable" => (int) setting('maxDriverOrderNotificationAtOnce', 1),
                    'informed' => [],
                    'ignored' => [],
                ];
                //push to firebase
                $path = "searchingOrders/" . $order->code . "";
                $this->pushToFirestore($newOrderData, $path);
            } catch (\Exception $ex) {
                logger("Skipping Order", [$order->id]);
                logger("Order Error", [$ex->getMessage() ?? '']);
            }
        }
    }
}
