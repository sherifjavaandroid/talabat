<?php

namespace App\Http\Livewire\Payment;

use App\Http\Livewire\BaseLivewireComponent;

class PaymentProcessedLivewire extends BaseLivewireComponent
{
    public $icon;
    public $title;
    public $message;
    public $queryString = ['title', 'message', 'icon'];


    public function render()
    {
        //
        return view('livewire.payment.processed')->layout('layouts.guest');
    }
}
