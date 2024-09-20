<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{

    public function index()
    {

        $wallet = Wallet::where('user_id',  Auth::id())->first();
        if (empty($wallet)) {
            $wallet = Wallet::create(
                ['user_id' =>  Auth::id()],
                ['balance' => 0.00]
            );
        }
        return $wallet;
    }

    public function topup(Request $request)
    {

        try {
            //
            $minimumTopupAmount = setting("minimumTopupAmount", 100);
            if ($request->amount < $minimumTopupAmount) {
                $msg = __("Amount is less than minimun topup amount") . " " . $minimumTopupAmount . "";
                return response()->json([
                    "message" => $msg,
                    "link" => route('wallet.topup.failed', ["code" => \Str::random(10), "amount" => $request->amount, 'msg' => $msg]),
                ]);
            }

            $amountToTopup = $request->amount;
            //throw exception if amount is not numeric, negative or zero
            if (!is_numeric($amountToTopup) || $amountToTopup <= 0) {
                throw new \Exception(__("Invalid Amount"), 1);
            }


            DB::beginTransaction();
            $wallet = $this->index();
            $walletTransaction = new WalletTransaction();
            $walletTransaction->amount = $request->amount;
            $walletTransaction->wallet_id = $wallet->id;
            $walletTransaction->is_credit = 1;
            $walletTransaction->reason = __("Topup");
            $walletTransaction->ref = "tp_" . \Str::random(10);
            $walletTransaction->status = "pending";
            $walletTransaction->save();
            DB::commit();

            $token = encrypt([
                "id" => $walletTransaction->id,
                "code" => $walletTransaction->ref,
                "user_id" => $walletTransaction->wallet->user_id,
            ]);

            return response()->json([
                "message" => "",
                "link" => route('wallet.topup', ["code" => $walletTransaction->ref]),
                "code" => $walletTransaction->ref,
                "token" => $token,
            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }


    public function transactions(Request $request)
    {
        return WalletTransaction::whereHas("wallet", function ($query) {
            return $query->where('user_id', Auth::id());
        })->orderBy('id', 'DESC')->paginate();
    }



    //transfer from my account to another account
    public function transferBalance(Request $request)
    {

        try {

            $minimumTopupAmount = setting("minimumTopupAmount", 100);
            if ($request->amount < $minimumTopupAmount) {
                throw new \Exception(__("Amount is less than minimun topup amount") . " " . $minimumTopupAmount . "", 1);
            }

            //validate that the amount sent is numeric and greater than zero
            $amountToTopup = $request->amount;
            //throw exception if amount is not numeric, negative or zero
            if (!is_numeric($amountToTopup) || $amountToTopup <= 0) {
                throw new \Exception(__("Invalid Amount"), 1);
            }


            //check if driver as enough in account before starting the transfer process
            $myWallet = $this->index();

            if ($myWallet->balance < $request->amount) {
                return response()->json([
                    "message" => __("Wallet balance is less than top-up amount")
                ], 400);
            }

            //
            DB::beginTransaction();

            //find the wallet of the other user
            $otherUserwallet = Wallet::firstOrCreate(
                ['user_id' =>  $request->user_id],
                ['balance' => 0.00]
            );

            //debit current user wallet
            $myWallet->balance = $myWallet->balance - $request->amount;
            $myWallet->save();
            //
            $walletTransaction = new WalletTransaction();
            $walletTransaction->amount = $request->amount;
            $walletTransaction->wallet_id = $myWallet->id;
            $walletTransaction->is_credit = 0;
            $walletTransaction->reason = __("Transfer");
            $walletTransaction->ref = "lt_" . \Str::random(10);
            $walletTransaction->status = "successful";
            $walletTransaction->save();

            // credit the customer/other user
            $otherUserwallet->balance = $otherUserwallet->balance + $request->amount;
            $otherUserwallet->save();
            $walletTransaction = new WalletTransaction();
            $walletTransaction->amount = $request->amount;
            $walletTransaction->wallet_id = $otherUserwallet->id;
            $walletTransaction->is_credit = 1;
            $walletTransaction->reason = __("Topup");
            $walletTransaction->ref = "lt_" . \Str::random(10);
            $walletTransaction->status = "successful";
            $walletTransaction->save();
            DB::commit();

            return response()->json([
                "message" => __("Account wallet topup successful")
            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "message" => $ex->getMessage()
            ], 400);
        }
    }
}
