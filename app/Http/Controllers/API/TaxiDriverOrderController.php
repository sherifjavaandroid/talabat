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
use App\Services\DriverAssignmentCheckService;
use App\Traits\FirebaseAuthTrait;
use App\Traits\GoogleMapApiTrait;
use App\Traits\TaxiTrait;
use App\Traits\OrderTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TaxiDriverOrderController extends Controller
{
    use GoogleMapApiTrait, TaxiTrait, OrderTrait;
    use FirebaseAuthTrait;
    //
    public function driverRejectAssignment(Request $request)
    {
        //
        try {
            //
            $order = Order::find($request->order_id);
            $orderRef = "newTaxiOrders/" . $order->code . "";
            //
            $firestoreClient = $this->getFirebaseStoreClient();
            $orderDocument = $firestoreClient->getDocument($orderRef);
            $ignoredDrivers = $orderDocument->getArray("ignoredDrivers") ?? [];
            array_push($ignoredDrivers, \Auth::id());
            //
            $firestoreClient->updateDocument(
                $orderRef,
                [
                    "ignoredDrivers" => $ignoredDrivers
                ],
            );

            //
            return response()->json([
                "message" => __("Driver reject order successul"),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage() ?? __("Driver reject order failed"),
            ], 400);
        }
    }



    public function driverAcceptAssignment(Request $request)
    {

        //
        $driver = User::find(Auth::id());
        $order = Order::find($request->order_id);

        //
        try {
            //driver check for assignment
            (new DriverAssignmentCheckService())->checkCanAssignOrder($order);
            //fetch order
            DB::beginTransaction();
            ////prevent driver from accepting a cancelled order
            if (empty($order)) {
                throw new Exception(__("Order could not be found"));
            } else if (in_array($order->status, ["cancelled", "delivered", "failed"])) {
                throw new Exception(__("Order has already been") . " " . $order->status);
            } else if (empty($order) || (!empty($request->driver_id) && !empty($order->driver_id))) {
                throw new Exception(__("Order has been accepted already by another delivery boy"));
            }


            //check if driver is on any uncompleted order
            $uncompletedOrder = Order::where("driver_id", $driver->id)
                ->otherCurrentStatus('failed', 'cancelled', 'delivered', 'completed')
                ->first();
            if ($order->taxi_order != null && !empty($uncompletedOrder)) {
                throw new Exception(__("You have an uncompleted order"));
            } else {
                $maxOnOrderForDriver = maxDriverOrderAtOnce($order);
                if ((int)$maxOnOrderForDriver <= $driver->assigned_orders) {
                    throw new Exception(__("You have reached the maximum number of orders you can accept at once"));
                }
            }
            //assigning driver to order
            $order->driver_id = $driver->id;
            $order->save();
            //set the order status to preparing
            $order->setStatus($request->status ?? 'preparing');

            DB::commit();
            $order->refresh();

            return response()->json([
                "message" => __("Order accepted and assigned"),
                "order" => Order::fullData()->where("id", $order->id)->first(),
            ], 200);
        } catch (\Exception $ex) {
            logger("Driver accpectance order error", [$ex]);
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }

    public function recordWalletDebit($wallet, $amount)
    {
        $walletTransaction = new WalletTransaction();
        $walletTransaction->wallet_id = $wallet->id;
        $walletTransaction->amount = $amount;
        $walletTransaction->reason = __("New Order");
        $walletTransaction->status = "successful";
        $walletTransaction->is_credit = 0;
        $walletTransaction->save();
    }
}
