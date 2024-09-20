<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayoutController extends Controller
{


    public function store(Request $request)
    {

        //
        try {
            //
            DB::beginTransaction();
            //new model
            $payout = new Payout();
            $payout->fill($request->all());

            //
            $authUser = User::find(\Auth::id());
            //if this a vendor manager 
            if ($authUser->hasRole('manager')) {
                //
                $earning = Earning::where('vendor_id', $request->vendor_id)->firstorfail();
                $payout->earning_id = $earning->id;
            } else if ($authUser->hasRole('driver')) {
                //
                $earning = Earning::where('user_id', $authUser->id)->firstorfail();
                $payout->earning_id = $earning->id;
            } else {
                return response()->json([
                    "message" => __("You are not allowed to perform this operation")
                ], 400);
            }
            //if account have enough to be debited
            if ($earning->amount < $request->amount) {
                return response()->json([
                    "message" => __("Insufficient earning amount")
                ], 400);
            }
            //sum all 

            //
            $payout->save();

            DB::commit();
            return response()->json([
                "message" => __("Payout request submitted. You will receive a notification once processed")
            ], 200);
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return response()->json([
                "user" => \Auth::user(),
                "message" => __("No earning avaialble")
            ], 400);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }
}
