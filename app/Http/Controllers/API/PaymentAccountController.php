<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentAccountController extends Controller
{

    public function index(Request $request)
    {

        //
        $authUser = User::find(\Auth::id());
        //if this a vendor manager 
        if ($authUser->hasRole('manager')) {
            $paymentAccounts = PaymentAccount::whereHasMorph('accountable', [Vendor::class], function ($query) use ($authUser) {
                return $query->where("id", $authUser->vendor_id);
            })->paginate();
        } else {
            $paymentAccounts = PaymentAccount::whereHasMorph('accountable', [User::class], function ($query) use ($authUser) {
                return $query->where("id", $authUser->id);
            })->paginate();
        }
        return response()->json($paymentAccounts, 200);
    }

    public function store(Request $request)
    {

        //
        try {
            //
            DB::beginTransaction();
            //new model
            $paymentAccount = new PaymentAccount();
            $paymentAccount->fill($request->all());

            //
            $authUser = User::find(\Auth::id());
            //if this a vendor manager 
            if ($authUser->hasRole('manager')) {
                //
                $vendor = Vendor::find($request->vendor_id);
                $paymentAccount->accountable()->associate($vendor)->save();
            } else {
                //
                $paymentAccount->accountable()->associate($authUser)->save();
            }

            //
            $paymentAccount->save();

            DB::commit();
            return response()->json([
                "data" => $paymentAccount
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {

        //
        try {
            //auth user
            $authUser = User::find(\Auth::id());
            //
            DB::beginTransaction();
            //old model
            $paymentAccount = PaymentAccount::find($id);

            //make sure only creator of account can update it
            if ($authUser->hasRole('manager') && $paymentAccount->accountable->id != $authUser->vendor_id) {
                return response()->json([
                    "message" => __("You are not allowed to perform this operation")
                ], 400);
            } else if (!$authUser->hasRole('manager') && $paymentAccount->accountable->id != $authUser->id) {
                return response()->json([
                    "message" => __("You are not allowed to perform this operation")
                ], 400);
            }



            //start updating details
            $paymentAccount->update($request->all());

            DB::commit();
            return response()->json([
                "data" => $paymentAccount
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }
}
