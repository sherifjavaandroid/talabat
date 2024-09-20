<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStop;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\PaymentMethod;
use App\Models\Wallet;
use App\Models\Vendor;
use App\Traits\OrderTrait;
use App\Traits\WalletTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;


class ParcelOrderService
{
    use OrderTrait, WalletTrait;

    public function __constuct()
    {
        //
    }


    public function placeOrder(Request $request, $userId = null)
    {

        //verify token
        //TODO: remove this in like future update
        if (!empty($request->token)) {

            try {
                $orderSummary = \Crypt::decrypt($request->token);
                //check if the total are the same
                if ($orderSummary["total"] != $request->total) {
                    throw new \Exception(__("Invalid Order Summary. Please contact support"), 1);
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                throw new \Exception(__("Invalid Order Summary. Please contact support"), 1);
            }
        }


        DB::beginTransaction();
        $order = new order();
        if ($userId != null) {
            $order->user_id = $userId;
        }
        $paymentLink = "";
        $message = "";

        // it will throw an exception if the order is not payable by wallet
        $this->isPayableByWallet();


        //DON'T TRANSLATE
        $order->vendor_id = $request->vendor_id;
        $order->payment_method_id = $request->payment_method_id;
        $order->note = $request->note ?? '';
        //
        $order->package_type_id = $request->package_type_id;
        $order->pickup_date = $request->pickup_date;
        $order->pickup_time = $request->pickup_time;
        // TODO take extra infos
        $order->weight = $request->weight ?? 0;
        $order->width = $request->width ?? 0;
        $order->length = $request->length ?? 0;
        $order->height = $request->height ?? 0;

        //
        if (\Schema::hasColumn('orders', 'payer')) {
            $order->payer = $request->payer;
        }

        $order->sub_total = $request->sub_total;
        $order->discount = $request->discount;
        $order->delivery_fee = $request->delivery_fee;
        $order->tax = $request->tax;
        $order->tax_rate = $request->tax_rate ?? Vendor::find($order->vendor_id)->tax ?? 0.00;
        $order->total = $request->total;
        if (\Schema::hasColumn("orders", 'fees')) {
            $order->fees = json_encode($request->fees ?? []);
        }
        $order->save();
        $order->setStatus($this->getNewOrderStatus($request));

        // allow old apps to still place order [Will be removed in future update]
        if (!empty($request->pickup_location_id)) {
            $orderStop = new OrderStop();
            $orderStop->order_id = $order->id;
            $orderStop->stop_id = $request->pickup_location_id;
            $orderStop->save();
        }

        //stops
        if (!empty($request->stops)) {
            foreach ($request->stops as $stop) {

                $orderStop = new OrderStop();
                $orderStop->order_id = $order->id;
                $orderStop->stop_id = $stop['stop_id'] ?? $stop['id'];
                $orderStop->price = $stop['price'] ?? 0.00;
                if (!empty($stop["name"])) {
                    $orderStop->name = $stop['name'] ?? '';
                    $orderStop->phone = $stop['phone'] ?? '';
                    $orderStop->note = $stop['note'] ?? '';
                }

                $orderStop->save();
            }
        }

        // allow old apps to still place order [Will be removed in future update]
        if (!empty($request->dropoff_location_id)) {
            $orderStop = new OrderStop();
            $orderStop->order_id = $order->id;
            $orderStop->stop_id = $request->dropoff_location_id;
            $orderStop->name = $request->recipient_name;
            $orderStop->phone = $request->recipient_phone;
            $orderStop->note = $request->note ?? '';
            $orderStop->save();
        }


        //save the coupon used
        $coupon = Coupon::where("code", $request->coupon_code)->first();
        //if discount is not zero and coupon is empty, throw error
        if (empty($coupon) && $request->discount > 0) {
            throw new \Exception(__("Invalid Coupon"), 1);
        }


        if (!empty($coupon)) {
            $couponUser = new CouponUser();
            $couponUser->coupon_id = $coupon->id;
            $couponUser->user_id = $userId ?? \Auth::id();
            $couponUser->order_id = $order->id;
            $couponUser->save();
        }

        //
        $response = $this->processWalletOrderPayment($request, $order);
        $paymentLink = $response["link"];
        $message = $response["message"];
        //
        $order->save();
        //
        DB::commit();

        //
        $paymentToken = encrypt([
            "id" => $order->id,
            "code" => $order->code,
            "user_id" => $order->user_id,
        ]);


        return response()->json([
            "message" => $message,
            "link" => $paymentLink,
            "code" => $order->code,
            "token" => $paymentToken,
        ], 200);
    }
}