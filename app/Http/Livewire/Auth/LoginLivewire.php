<?php

namespace App\Http\Livewire\Auth;

use App\Http\Livewire\BaseLivewireComponent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginLivewire extends BaseLivewireComponent
{
    public $email;
    public $password;
    public $remember = false;



    public function loadAccount(int $type = 0)
    {
        if ($type == 0) {
            $this->email = "admin@demo.com";
        } else if ($type == 1) {
            $this->email = "manager@demo.com";
        } else if ($type == 2) {
            $this->email = "manager1@demo.com";
        } else if ($type == 3) {
            $this->email = "city-admin@demo.com";
        } else if ($type == 4) {
            $this->email = "manager3@demo.com";
        } else {
            $this->email = "client@demo.com";
        }
        $this->password = "password";
    }

    public function login()
    {


        $this->validate(
            [
                "email" => "required|email|exists:users",
                "password" => "required|string",
            ],
            [
                "email.exists" => __("Email not associated with any account")
            ]
        );

        //
        $user = User::where('email', $this->email)->first();

        if ($user->hasAnyRole('client', 'driver')) {
            $this->showErrorAlert("Unauthorized Access");
            return;
        } else if (!$user->is_active) {
            $this->showErrorAlert(__("Account is not active. Please contact us"));
            return;
        }

        if (Hash::check($this->password, $user->password) && Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            //
            $user->syncFCMToken($this->fcmToken);
            return redirect()->intended(route('dashboard'));
        } else {
            $this->showErrorAlert(__("Invalid Credentials. Please check your credentials and try again"));
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}
