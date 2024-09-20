<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use App\Models\Otp;
use App\Traits\FirebaseAuthTrait;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;


class OTPController extends Controller
{

    //traits
    use FirebaseAuthTrait;


    public function sendOTP(Request $request)
    {

        //is_login
        //phone
        //verifiy that the number exists
        if (!empty($request->is_login)) {
            //
            $user = User::where('phone', $request->phone)->first();
            if (empty($user)) {
                return response()->json([
                    "message" => __('Phone number not associated with any account'),
                ], 401);
            }
        }


        ////verification code
        $code = rand(111111, 999999);
        //create or update otp record
        Otp::updateOrCreate(
            ["phone" => $request->phone],
            ["code" => $code]
        );

        //send the verification code
        $message = "[" . setting('appName', env('APP_NAME')) . "] " . __("Verification Code") . ": " . $code . ".";

        try {
            $otpService = new OTPService();
            $instructions = $otpService->sendOTP($request->phone, $message, null, $user ?? null, $code);
            return response()->json([
                "message" => __('OTP sent successfully'),
                "instructions" => $instructions,
            ], 200);
        } catch (\Exception $ex) {
            logger("Send OTP inssue", [$ex]);
            return response()->json([
                "message" => __('OTP failed to send to provided phone number'),
            ], 400);
        }
    }

    public function verifyOTP(Request $request)
    {

        //is_login
        //phone
        //code

        //
        $otp = Otp::where(
            [
                "phone" => $request->phone,
                "code" => $request->code,
            ]
        )->first();


        //invlaid
        if (empty($otp)) {
            //
            return response()->json([
                "message" => __('Invalid OTP'),
            ], 400);
        }

        //
        $otp->delete();
        if (empty($request->is_login)) {
            $phoneNumber = $request->phone;
            //remove white spaces
            $phoneNumber = str_replace(' ', '', $phoneNumber);
            $verificationData = [
                "phone" => $phoneNumber,
                "code" => $request->code,
            ];
            return response()->json([
                "message" => __('OTP verification successful'),
                "token" => encrypt($verificationData)
            ], 200);
        } else {
            //
            $user = User::where('phone', $request->phone)->first();
            $authController = new AuthController();
            return $authController->authObject($user);
        }
    }


    public function verifyFirebaseToken(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'phone:' . setting('countryCode', "GH"),
            ],
            $messages = [
                'phone.exists' => __('Phone not associated with any account'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        //
        $phone = new PhoneNumber($request->phone);
        $user = User::where('phone', 'like', '%' . $phone . '')->first();

        if (empty($user)) {
            return response()->json([
                "message" => __("There is no account associated with provided phone number ") . $phone . "",
            ], 400);
        }

        //verify firebase token
        try {

            //
            $phone = new PhoneNumber($request->phone);

            if (!empty($request->firebase_id_token)) {
                $firebaseUser = $this->verifyFirebaseIDToken($request->firebase_id_token);

                //verify that the token belongs to the right user
                if ($firebaseUser->phoneNumber == $phone) {

                    // $user = User::where('phone', $request->phone)->first();
                    $authController = new AuthController();
                    return $authController->authObject($user);
                } else {
                    return response()->json([
                        "message" => __("Invalid credentials. Please check your phone and try again"),
                    ], 400);
                }
            } else {
                //verify that the token belongs to the right user
                return response()->json([
                    "message" => __("Invalid Account"),
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage(),
            ], 400);
        }
    }
}