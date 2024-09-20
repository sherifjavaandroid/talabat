<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Earning;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class EarningController extends Controller
{
    //
    public function user(Request $request)
    {

        $currency = Currency::active()->first();
        // $earning = Earning::where('user_id', Auth::id())->first();
        // if (empty($earning)) {
        //     $earning = new Earning();
        //     $earning->amount = 0.00;
        //     $earning->user_id = Auth::id();
        //     $earning->save();
        // }

        $earning = Earning::firstOrCreate(
            ['user_id' =>  Auth::id()],
            ['amount' => 0.00]
        );

        // $user = User::find(Auth::id());
        // if( $user->hasAnyRole('driver') && setting('enableDriverWallet', "0") ){
        //     $wallet = Wallet::firstOrCreate(
        //         ['user_id' =>  Auth::id()],
        //         ['balance' => 0.00]
        //     );
        //     $earning->amount = $wallet->balance;
        // }
        //
        return response()->json([
            "currency" => $currency,
            "earning" => $earning,
        ], 200);
    }


    //
    public function vendor(Request $request)
    {
        $currency = Currency::active()->first();
        $earning = Earning::with('vendor')->where('vendor_id', Auth::user()->vendor_id )->first();
        if (empty($earning)) {
            $earning = new Earning();
            $earning->amount = 0.00;
            $earning->vendor_id = Auth::user()->vendor_id;
            $earning->save();
        }
        //
        return response()->json([
            "currency" => $currency,
            "earning" => $earning,
        ], 200);
    }
}
