<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\OrderStop;
use App\Models\PaymentMethod;
use App\Models\Wallet;
use App\Services\DriverAssignmentCheckService;
use App\Services\ParcelOrderService;
use App\Services\RegularOrderService;
use App\Services\ServiceOrderService;
use App\Traits\OrderTrait;
use App\Traits\WalletTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    use OrderTrait, WalletTrait;
    //
    public function index(Request $request)
    {

        //
        $driverId = $request->driver_id;
        $vendorId = $request->vendor_id;
        $status = $request->status;
        $type = $request->type;
        $vendorTypeId = $request->vendor_type_id;


        $orders = Order::fullData()
            ->when(!empty($vendorId), function ($query) use ($vendorId) {
                return $query->orWhere('vendor_id', $vendorId);
            })
            ->when(!empty($driverId), function ($query) use ($driverId) {
                return $query->orWhere('driver_id', $driverId);
            })
            ->when(empty($vendorId) && empty($driverId), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->when(!empty($status), function ($query) use ($status) {
                // return $query->where('status', $status);
                return $query->currentStatus($status);
            })
            ->when($type == "history", function ($query) {
                // return $query->whereIn('status', ['failed', 'cancelled', 'delivered']);
                return $query->currentStatus(['failed', 'cancelled', 'delivered']);
            })
            ->when($type == "assigned", function ($query) {
                // return $query->whereNotIn('status', ['failed', 'cancelled', 'delivered']);
                return $query->otherCurrentStatus(['failed', 'cancelled', 'delivered']);
            })
            ->when($vendorTypeId, function ($query) use ($vendorTypeId) {
                return $query->whereHas("vendor", function ($query) use ($vendorTypeId) {
                    return $query->where('vendor_type_id', $vendorTypeId);
                });
            })
            ->orderBy('created_at', 'DESC')->paginate();
        return $orders;
    }

    public function store(Request $request)
    {

        //if the new order if for packages
        if ($request->type == "package" || $request->type == "parcel") {
            return $this->processPackageDeliveryOrder($request);
        } else if ($request->type == "service") {
            return $this->processServiceOrder($request);
        }


        //manual validation
        //throw error if photo or photos is not present and payment_method_id is not present

        if (!$request->has("photo") && !$request->has("photos") && !$request->has("payment_method_id")) {
            return response()->json([
                "message" => __("Payment Method is required"),
            ], 400);
        }

        //regular order
        //validate request
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required_without:data|exists:vendors,id',
            'data' => 'required_without:vendor_id',
            'delivery_address_id' => 'sometimes|nullable|exists:delivery_addresses,id',
            'sub_total' => 'required|numeric',
            'discount' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ]);





        if ($validator->fails()) {

            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }


        //
        try {

            //check wallet balance if wallet is selected before going further
            $paymentMethod = PaymentMethod::find($request->payment_method_id ?? 0);
            //wallet check
            if (!empty($paymentMethod) && $paymentMethod->is_cash && $paymentMethod->slug == "wallet") {

                $wallet = Wallet::mine()->first();
                if (empty($wallet) || $wallet->balance < $request->total) {
                    throw new \Exception(__("Wallet Balance is less than order total amount"), 1);
                }
            }


            //proces regular single vendor order
            if (!$request->has("data")) {
                return (new RegularOrderService())->singleOrder($request);
            }
            //for multiple vendor ordering
            return (new RegularOrderService())->multipleVendorOrder($request);
        } catch (\Exception $ex) {
            logger("order error", [$ex]);
            \Log::info([
                "Error" => $ex->getMessage(),
                "File" => $ex->getFile(),
                "Line" => $ex->getLine(),
            ]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage(),
                "errorCode" => $ex->getCode(),
            ], 400);
        }
    }


    ///handle package order
    public function processPackageDeliveryOrder($request)
    {

        //validate request
        $validator = Validator::make($request->all(), [
            'package_type_id' => 'required|exists:package_types,id',
            'vendor_id' => 'required|exists:vendors,id',
            'pickup_location_id' => 'sometimes|exists:delivery_addresses,id',
            'dropoff_location_id' => 'sometimes|exists:delivery_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'weight' => 'sometimes|nullable|numeric',
            'width' => 'sometimes|nullable|numeric',
            'length' => 'sometimes|nullable|numeric',
            'height' => 'sometimes|nullable|numeric',
            'sub_total' => 'required|numeric',
            'discount' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }


        //saving to database
        try {
            return (new ParcelOrderService())->placeOrder($request);
        } catch (\Exception $ex) {
            \Log::info([
                "Error" => $ex->getMessage(),
                "Line" => $ex->getLine(),
            ]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage(),
                "errorCode" => $ex->getCode(),
            ], 400);
        }
    }

    ///handle serivce order
    public function processServiceOrder($request)
    {

        //validate request
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'service_id' => 'required|exists:services,id',
            'delivery_address_id' => 'sometimes|nullable|exists:delivery_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'sub_total' => 'required|numeric',
            'discount' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            "token" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }


        //saving to database
        try {

            return (new ServiceOrderService())->placeOrder($request);
        } catch (\Exception $ex) {
            \Log::info([
                "Error" => $ex->getMessage(),
                "Line" => $ex->getLine(),
            ]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage(),
                "errorCode" => $ex->getCode(),
            ], 400);
        }
    }

    public function show(Request $request, $id)
    {
        //
        $order = Order::fullData()->where('id', $id)->first();
        return $order;
    }



    //
    public function update(Request $request, $id)
    {
        //
        $user = User::find(Auth::id());
        $order = Order::find($id);
        $ownerOfOrder = $user->hasAnyRole('client') && $user->id == $order->user_id;

        if ($user->hasAnyRole('client') && $user->id != $order->user_id && !in_array($request->status, ['pending', 'cancelled'])) {
            return response()->json([
                "message" => "Order doesn't belong to you"
            ], 400);
        }

        //
        try {
            //driver check for assignment
            (new DriverAssignmentCheckService())->checkCanAssignOrder($order);
            //fetch order
            DB::beginTransaction();
            $order = Order::find($id);
            ////prevent driver from accepting a cancelled order
            if (empty($order)) {
                throw new Exception(__("Order could not be found"));
            } else if (in_array($order->status, ["cancelled", "delivered", "failed"])) {
                throw new Exception(__("Order has already been") . " " . __($order->status));
            } else if (empty($order) || (!empty($request->driver_id) && !empty($order->driver_id))) {
                throw new Exception(__("Order has been accepted already by another delivery boy"));
            }

            //assigning driver to order
            if (!empty($request->driver_id)) {
                $order->driver_id = $request->driver_id;
                $order->save();
            }

            //updating payment status
            if (!empty($request->payment_status) && $ownerOfOrder) {
                //if payment is COD
                $paymentMethod = PaymentMethod::find($request->payment_method_id);
                if (!empty($paymentMethod) && $paymentMethod->slug == "cash") {
                    //TODO
                    $order->payment_status = "pending";
                } else {

                    $order->payment_status = $request->payment_status;
                }

                //debit user wallet
                if (empty($order->payment_method_id) && !empty($request->payment_method_id)) {
                    //wallet check
                    if ($paymentMethod->slug == "wallet") {
                        //
                        $wallet = Wallet::mine()->first();
                        if (empty($wallet) || $wallet->balance < $order->total) {
                            throw new \Exception(__("Wallet Balance is less than order total amount"), 1);
                        } else {
                            //
                            $wallet->balance -= $order->total;
                            $wallet->save();

                            //RECORD WALLET TRANSACTION
                            $this->recordWalletDebit($wallet, $order->total);
                            //mark order payment has successful
                            $order->payment_status = "successful";
                        }
                    }
                }
                $order->save();
            }

            //updating order with request data
            $order->update($request->all());

            //for signature
            if ($request->hasFile("signature")) {
                $order->addMedia($request->signature->getRealPath())->toMediaCollection($request->proof_type ?? "signature");
            }




            //
            if (!empty($request->status)) {
                //prevent cancellation of an already preparing/ready order
                $statusCheck = setting('finance.preventOrderCancellation', null);
                //incase admin is yet to set any status for cancellations
                if ($statusCheck == null) {
                    $statusCheck = ["scheduled", "pending"];
                    if (!empty($order->taxi_order)) {
                        $statusCheck = ["scheduled", "pending", "preparing", "ready"];
                    }
                } else {
                    if (!is_array($statusCheck)) {
                        $statusCheck = json_decode(setting('finance.preventOrderCancellation', ""), true) ?? [];
                    }
                }
                //
                if (
                    in_array($request->status, ["cancelled", "cancel"]) && in_array($order->status, $statusCheck)
                    // && empty($order->driver_id)
                ) {
                    throw new Exception(__("Order can't be cancelled."));
                }
                $order->setStatus($request->status);
            }

            DB::commit();
            $order->refresh();

            return response()->json([
                "message" => __("Order placed ") . __($order->status) . "",
                "order" => Order::fullData()->where("id", $id)->first(),
            ], 200);
        } catch (\Exception $ex) {
            logger("order error", [$ex]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }


    //



}