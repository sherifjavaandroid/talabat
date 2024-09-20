<?php

namespace App\Http\Livewire\Auth;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ForgotPasswordLivewire extends BaseLivewireComponent
{

    public $phone;
    public $setPassword = false;
    public $otp;
    public $idToken;
    public $password;
    public $password_confirmation;
    protected $listeners = ['allowReset' => 'showResetForm'];


    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.auth');
    }


    public function initiateFireabse()
    {
        $this->emit('initiateFirebaseAuth', setting('apiKey', ""));
    }

    public function resetPassword()
    {

        $countryCodeValidate = setting('countryCode', 'GH');
        $this->validate(
            ["phone" => "phone:$countryCodeValidate|exists:users"],
            ["phone.exists" => __("No account associated with phone")]
        );

        //firebase
        if ($this->isFirebaseOTP()) {
            $this->emit('sendOTP', $this->phone);
        } else {

            //http
            $url = route('otp.send');
            $response = Http::post($url, [
                "phone" => $this->phone,
                "is_login" => false
            ]);
            $msg = $response->json()['message'] ?? null;
            //
            if ($response->successful()) {
                $this->showSuccessAlert($msg ?? __("OTP sent to your phone number"));
                $this->dispatchBrowserEvent('show-verify');
            } else {
                $this->showErrorAlert($msg ?? __("OTP failed to send to provided phone number"));
            }
        }
    }

    //
    public function verifyOTP()
    {

        $this->validate([
            "otp" => "required|size:6"
        ]);

        //firebase
        if ($this->isFirebaseOTP()) {
            $this->emit('verifyFirebaseAuth', $this->otp);
        } else {

            //http
            $url = route('otp.verify');
            $response = Http::post($url, [
                "phone" => $this->phone,
                "code" => $this->otp,
                "is_login" => false
            ]);
            $msg = $response->json()['message'] ?? null;
            //
            if ($response->successful()) {
                $this->showSuccessAlert($msg ?? __("OTP verification successful"));
                $idToken = \Str::random(60);
                $this->showResetForm($idToken);
            } else {
                $this->showErrorAlert($msg ?? __("OTP verification failed"));
            }
        }
    }


    public function isFirebaseOTP()
    {
        $otpGateway = setting('otpGateway');
        $otpGateway = strtolower($otpGateway);
        return $otpGateway == "firebase";
    }



    public function showResetForm($idToken)
    {
        $this->idToken = $idToken;
        $this->setPassword = true;
    }

    public function saveNewPassword()
    {

        $this->validate([
            "password" => 'required|min:6',
            "password_confirmation" => 'required|same:password|min:6',
        ]);

        //
        if (!empty($this->idToken)) {
            $user = User::where('phone', $this->phone)->first();
            $user->password = Hash::make($this->password);
            $user->Save();

            //
            $this->phone = "";
            $this->setPassword = false;
            $this->showSuccessAlert(__("Account password updated. You can now login with the newly created account password"));
        }
    }
}
