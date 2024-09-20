<?php

namespace App\Http\Livewire\Payment;

use App\Http\Livewire\BaseLivewireComponent;

class WalletTopUpFailureLivewire extends BaseLivewireComponent
{

    public $amount;
    public $msg;
    protected $queryString = ['amount','msg'];
   
    public function render()
    {
        //
        return view('livewire.payment.topup-failed')->layout('layouts.guest');
    }

}
