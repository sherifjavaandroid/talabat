<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;

class MySubscriptionLivewire extends BaseLivewireComponent
{


    public function render()
    {
        return view('livewire.my_subscriptions');
    }

   
}


