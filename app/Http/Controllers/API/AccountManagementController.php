<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class AccountManagementController extends Controller
{
   
    //
    public function delete(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        //check old password 
        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                "message" => __("Invalid Current Password"),
            ], 400);
        }


        try {

            $this->isDemo();
            DB::beginTransaction();
            //
            $user = User::find(Auth::id());
            $user->email = "".config('custom_config.account_remove_code').$user->email;
            $user->phone = "".config('custom_config.account_remove_code').$user->phone;
            $user->save();
            $user->delete();
            DB::commit();
            //generate tokens
            return response()->json([
                "message" => __("Account deleted successfully"),
                "user" => $user,
            ]);
        } catch (Exception $error) {
            DB::rollback();
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
}
