<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyPointReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class LoyaltyPointController extends Controller
{

    public function current(Request $request)
    {
        $loyaltyPoint = LoyaltyPoint::where("user_id", Auth::id())->first();
        if ($loyaltyPoint == null) {
            $loyaltyPoint = new LoyaltyPoint();
            $loyaltyPoint->user_id = Auth::id();
            $loyaltyPoint->points = 0;
            $loyaltyPoint->save();
        }
        return $loyaltyPoint;
    }

    public function report(Request $request)
    {
        $loyaltyPoint = $this->current($request);
        return LoyaltyPointReport::whereLoyaltyPointId($loyaltyPoint->id)->latest()->paginate();
    }

    public function withdraw(Request $request)
    {
        try {



            $enableLoyalty = (bool) setting('finance.enableLoyalty', false);
            if (!$enableLoyalty) {
                throw new \Exception(__("Loyalty Points can't be redeemed. Please contact support"), 1);
            }

            $points = $request->points;
            //throw exception if points is not numeric, negative or zero
            if (!is_numeric($points) || $points <= 0) {
                throw new \Exception(__("Invalid Points"), 1);
            }

            DB::beginTransaction();
            //
            $loyaltyPoint = LoyaltyPoint::firstOrCreate(["user_id" => \Auth::id()], ["points" => 0]);
            $myWallet = Wallet::firstOrCreate(
                ['user_id' =>  \Auth::id()],
                ['balance' => 0.00]
            );

            //check if user has enough in wallet
            if ($loyaltyPoint->points < $points) {
                throw new \Exception(__('Insufficient Points'), 1);
            }

            //convert points to amount
            $convertedPointsToAmount = setting('finance.point_to_amount', 0.001) * $points;
            //update user wallet
            $newWalletBalance = $myWallet->balance + $convertedPointsToAmount;
            $loyaltyPoint->user->updateWallet(
                $newWalletBalance,
                $reason = __("Loyalty point withdrawal"),
            );

            //dedut points from current points
            $loyaltyPoint->points -= $points;
            $loyaltyPoint->save();

            //record the report
            $loyaltyPointReport = new LoyaltyPointReport();
            $loyaltyPointReport->loyalty_point_id = $loyaltyPoint->id;
            $loyaltyPointReport->points = $points;
            $loyaltyPointReport->amount = $convertedPointsToAmount;
            $loyaltyPointReport->is_credit = false;
            $loyaltyPointReport->save();
            DB::commit();

            return response()->json([
                "message" => __("Loyalty point withdrawal successful"),
                "wallet" => $myWallet->refresh(),
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();

            return response()->json([
                "message" => $ex->getMessage() ??  __("Invalid Data")
            ], 400);
        }
    }
}
