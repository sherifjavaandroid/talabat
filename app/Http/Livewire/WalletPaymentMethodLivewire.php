<?php

namespace App\Http\Livewire;


use App\Models\PaymentMethodVehicleType;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletPaymentMethodLivewire extends BaseLivewireComponent
{

  

    public function render()
    {
        return view('livewire.wallet_payment_methods');
    }


}
