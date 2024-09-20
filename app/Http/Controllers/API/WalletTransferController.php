<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Propaganistas\LaravelPhone\PhoneNumber;


class WalletTransferController extends Controller
{

    public function walletAddress(Request $request)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' =>  \Auth::id()],
            ['balance' => 0.00]
        );
        $token = encrypt([
            "id" => $wallet->id,
            "user_id" => $wallet->user_id,
        ]);


        $user = User::find(\Auth::id());

        //response
        return response()->json([
            "id" => $user->id,
            "photo" => $user->photo,
            "email" => $user->email,
            "phone" => $user->phone,
            "name" => $user->name,
            "wallet_address" => $token,
        ], 200);
    }


    public function walletAddressSearch(Request $request)
    {

        $keyword = $request->keyword;
        $phoneKeywrod = $request->keyword;
        if (!empty($keyword)) {

            //
            $fullInfoRequired = (bool) setting('finance.fullInfoRequired', false);
            try {
                if (substr($keyword, 0, strlen("+")) != "+") {
                    $phoneKeywrod = "+" . $phoneKeywrod;
                }
                $phoneKeywrod = (string) (new PhoneNumber($phoneKeywrod, setting('countryCode', "GH")))->formatE164();
            } catch (\Exception $ex) {
                // logger("Error", [$ex]);
            }

            //query
            $users = User::active()->when($fullInfoRequired, function ($query) use ($keyword, $phoneKeywrod) {
                return $query->where(function ($query) use ($keyword, $phoneKeywrod) {
                    return $query->where('email',  $keyword)
                        ->orWhere('phone', $phoneKeywrod);
                });
            }, function ($query) use ($keyword, $phoneKeywrod) {
                return $query->where(function ($query) use ($keyword, $phoneKeywrod) {
                    $query->where('email', 'like', $keyword . '%')
                        ->orWhere('phone', 'like', $phoneKeywrod . '%');
                });
            })
                ->where('id', '!=', \Auth::id())
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    //
                    $wallet = Wallet::firstOrCreate(
                        ['user_id' =>  $user->id],
                        ['balance' => 0.00]
                    );
                    $token = encrypt([
                        "id" => $wallet->id,
                        "user_id" => $user->id,
                    ]);

                    $user->wallet_address = $token;
                    return $user;
                });

            //
            return response()->json([
                "users" => $users,
            ], 200);
        } else {
            return response()->json([
                "users" => [],
            ], 200);
        }
    }



    public function walletTransfer(Request $request)
    {



        try {

            DB::beginTransaction();
            //
            try {
                $recipientWalletInfo = decrypt($request->wallet_address);
            } catch (\Exception $e) {
                throw new \Exception(__('Invalid Wallet Address'), 1);
            }
            //
            $recipientWallet = $this->getUserWallet($recipientWalletInfo["user_id"]);
            $senderWallet = $this->getUserWallet(\Auth::id());
            $transferAmount = $request->amount;
            if ($recipientWallet->id == $senderWallet->id) {
                throw new \Exception(__('Self transfer is not allowed. Please select another account to send to. Thank you'), 1);
            }
            //check if user has enough in wallet
            if ($senderWallet->balance < $transferAmount) {
                throw new \Exception(__('Insufficient Wallet Balance'), 1);
            }
            //check if the provided password match account password
            if (!\Hash::check($request->password, \Auth::user()->password)) {
                throw new \Exception(__("Invalid account password"));
            }


            //transfer to recipientWallet
            $recipientWallet->user->topupWallet(
                $transferAmount,
                $reason = __("Wallet Transfer from: ") . $senderWallet->user->name . "",
            );
            //reduce the wallet balance of sender
            $newSenderBalance = $senderWallet->balance - $transferAmount;
            $senderWallet->user->updateWallet(
                $newSenderBalance,
                $reason = __("Wallet Transfer to: ") . $recipientWallet->user->name . "",
            );
            DB::commit();

            return response()->json([
                "message" => __("Wallet transfer successful"),
                "wallet" => $senderWallet->refresh(),
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();

            return response()->json([
                "message" => $ex->getMessage() ??  __("Invalid Data")
            ], 400);
        }
    }

    public function getUserWallet($userId)
    {
        return Wallet::firstOrCreate(
            ['user_id' =>  $userId],
            ['balance' => 0.00]
        );
    }
}
