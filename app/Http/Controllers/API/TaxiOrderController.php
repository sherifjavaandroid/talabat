<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TaxiOrder;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\DeliveryAddress;
use App\Models\VehicleType;
use App\Models\PaymentMethod;
use App\Models\TaxiZone;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Vehicle;
use App\Models\WalletTransaction;
use App\Traits\FirebaseAuthTrait;
use App\Traits\GoogleMapApiTrait;
use App\Traits\TaxiTrait;
use App\Traits\OrderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class TaxiOrderController extends Controller
{
    use GoogleMapApiTrait, TaxiTrait, OrderTrait;
    use FirebaseAuthTrait;


    //check if taxi is avaliable in user currenct location
    public function location_available(Request $request)
    {

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $taxiZonesCount = TaxiZone::count();
        $taxiZone = TaxiZone::closeTo($latitude, $longitude)->first();

        if ($taxiZonesCount == 0 ||  !empty($taxiZone)) {
            return response()->json([
                "message" => __("Service location"),
            ], 200);
        } else {
            return response()->json([
                "message" => __("Does not service location"),
            ], 400);
        }
    }

    //fetch recent order location histry
    public function location_history(Request $request)
    {

        $taxiOrders = TaxiOrder::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->groupBy('dropoff_address')
            ->latest()->limit(10)->get();
        $taxiOrders->transform(function ($item, $key) {
            $address = new DeliveryAddress();
            $address->latitude = $item->dropoff_latitude ?? "";
            $address->longitude = $item->dropoff_longitude ?? "";
            $address->address = $item->dropoff_address ?? "";
            $address->name = $item->dropoff_address ?? "";
            return $address;
        });
        return response()->json($taxiOrders, 200);
    }


    //
    public function book(Request $request)
    {

        //
        try {
            //
            if (empty($request->vehicle_type)) {
                $vehicleType = VehicleType::find($request->vehicle_type_id);
            } else {
                $vehicleType = \Crypt::decrypt($request->vehicle_type);
            }
            //recalculate order price
            $pickupObject = "" . $request->pickup["lat"] . "," . $request->pickup["lng"] . "";
            $dropoffObject = "" . $request->dropoff["lat"] . "," . $request->dropoff["lng"] . "";
            $vehicleType->total = $this->getTaxiOrderTotalPrice($vehicleType, $pickupObject, $dropoffObject);
            //if custom has edit price
            if (number_format($vehicleType->total, 2) > number_format($request->sub_total, 2)) {
                // throw new \Exception("An error occured while trying to book your trip:: Amount issue ::Total ==>".$vehicleType->total.":: Sub ==>".$request->sub_total."", 1);
                //
                throw new \Exception(__("An error occured while trying to book your trip  Amount issue"), 1);
            }

            //also check if user over applied coupon code

            //check if the order total is less than the max order amount for the payment method
            $this->isOrderAmountPaymentmethodValid($request->payment_method_id, $request->total);




            //save new order
            //
            DB::beginTransaction();
            $order = new order();
            $order->code = $this->generateOrderCode(10);
            //add user_id if the request has user_id and the auth user has 'new-taxi-order' permission
            if ($request->has('user_id') && Auth::user()->can('new-taxi-order')) {
                $order->user_id = $request->user_id;
            } else {
                $order->user_id = Auth::id();
            }
            //verify payment method is valid and can handle the order amount
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $paymentLink = "";
            $message = "";

            if ($paymentMethod->is_cash) {

                //wallet check
                if ($paymentMethod->slug == "wallet") {
                    //
                    $wallet = Wallet::firstOrCreate(
                        ['user_id' => $request->user_id ?? Auth::id()],
                        ['balance' => 0.00]
                    );

                    //check if wallet balance is less than order total
                    if (empty($wallet) || $wallet->balance < $request->total) {
                        throw new \Exception(__("Wallet Balance is less than order total amount"), 1);
                    } else {
                        //
                        $wallet->balance -= $request->total;
                        $wallet->save();

                        //RECORD WALLET TRANSACTION
                        $this->recordWalletDebit($wallet, $request->total, $order);
                    }
                    $order->payment_status = "successful";
                }
                // $order->saveQuietly();
                $message = __("Order placed successfully. Relax while the vendor process your order");
            } else {
                $message = __("Order placed successfully. Please follow the link to complete payment.");
            }


            //rest order data
            $order->payment_method_id = $request->payment_method_id;
            $order->sub_total = $request->sub_total;
            $order->discount = $request->discount ?? 0;
            $order->tip = $request->tip ?? 0.00;
            $order->tax = $request->tax ?? 0.00;
            $order->total = $request->total;
            $order->pickup_date = $request->pickup_date;
            $order->pickup_time = $request->pickup_time;
            $order->payment_status ??= "pending";
            if (Schema::hasColumn("orders", 'fees')) {
                $order->fees = json_encode($request->fees ?? []);
            }
            $order->save();
            $order->setStatus($this->getNewOrderStatus($request));

            //save the coupon used
            $coupon = Coupon::where("code", $request->coupon_code)->first();
            if (!empty($coupon)) {
                $couponUser = new CouponUser();
                $couponUser->coupon_id = $coupon->id;
                $couponUser->user_id = $request->user_id ?? Auth::id();
                $couponUser->order_id = $order->id;
                $couponUser->save();
            }


            //taxi_order
            $taxiOrder = new TaxiOrder();
            $taxiOrder->order_id = $order->id;
            $taxiOrder->currency_id = $vehicleType->currency->id ?? null;
            $taxiOrder->vehicle_type_id = $vehicleType->id;
            //pickup
            $taxiOrder->pickup_latitude = $request->pickup["lat"];
            $taxiOrder->pickup_longitude = $request->pickup["lng"];
            $taxiOrder->pickup_address = $request->pickup["address"];
            //dropoff
            $taxiOrder->dropoff_latitude = $request->dropoff["lat"];
            $taxiOrder->dropoff_longitude = $request->dropoff["lng"];
            $taxiOrder->dropoff_address = $request->dropoff["address"];
            //fare breakdown
            try {
                $taxiOrder->base_fare = $vehicleType->base_fare;
                $taxiOrder->distance_fare = $vehicleType->distance_fare;
                $taxiOrder->time_fare = $vehicleType->time_fare;
                $taxiOrder->trip_distance = $vehicleType->trip_distance;
                $taxiOrder->trip_time = $vehicleType->trip_time;
            } catch (\Exception $e) {
                logger("Taxi order Breakdown error", [$e]);
            }
            $taxiOrder->save();

            //
            $order->save();
            $order->refresh();

            //generate payment link, if payment is pending
            if ($order->payment_status == "pending" && !$paymentMethod->is_cash) {
                $paymentLink = route('order.payment', ["code" => $order->code]);
            }

            //
            DB::commit();

            return response()->json([
                "order" => $order,
                "message" => $message,
                "link" => $paymentLink,
            ], 200);
        } catch (\Exception $ex) {
            logger("Taxi order", [$ex]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage() ?? __("An error occured while trying to book your trip")
            ], 400);
        }
    }

    //
    public function current(Request $request)
    {
        $authUser = User::find(Auth::id());
        $taxiBookingOrder = Order::with('driver.vehicle')->otherCurrentStatus(['failed', 'cancelled', 'delivered', 'scheduled'])
            ->whereHas('taxi_order')
            ->when($authUser->hasRole("driver"), function ($q) {
                return $q->where('driver_id', Auth::id());
            }, function ($q) {
                return $q->where('user_id', Auth::id());
            })
            ->first();
        return response()->json([
            "order" => $taxiBookingOrder,
        ], 200);
    }

    public function currentRateable(Request $request)
    {
        $taxiBookingOrder = Order::with('driver.vehicle')->currentStatus(['delivered', 'success', 'successful'])
            ->whereHas('taxi_order')
            ->whereDoesntHave('reviews')
            ->where('user_id', Auth::id())
            ->first();
        return response()->json([
            "order" => $taxiBookingOrder,
        ], 200);
    }

    //
    //
    public function cancelOrder(Request $request, $id)
    {

        //
        try {
            $taxiBookingOrder = Order::whereHas('taxi_order')->where('id', $id)->first();
            $taxiBookingOrder->setStatus("cancelled");
            return response()->json([
                "message" => __("Trip cancelled successfully"),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => __("Trip cancellation failed"),
            ], 400);
        }
    }

    //
    public function driverInfo(Request $request, $id)
    {

        //
        try {
            $driver = User::role('driver')->where('id', $id)->first();
            $driverVehicle = Vehicle::where('driver_id', $id)->first();

            //
            return response()->json([
                "driver" => $driver,
                "vehicle" => $driverVehicle,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage() ?? __("Driver info failed"),
            ], 400);
        }
    }


    public function recordWalletDebit($wallet, $amount, $order)
    {
        $walletTransaction = new WalletTransaction();
        $walletTransaction->wallet_id = $wallet->id;
        $walletTransaction->amount = $amount;
        $walletTransaction->reason = __("Taxi Order Payment") . " - #" . $order->code;
        $walletTransaction->status = "successful";
        $walletTransaction->is_credit = 0;
        $walletTransaction->save();
    }
}