<?php

namespace App\Http\Livewire;

use App\Models\Order;


class OrderPrintLivewire extends BaseLivewireComponent
{

    public $orderCode;
    public $selectedModel;



    public function mount($code)
    {
        $this->orderCode = $code;
        $this->selectedModel = Order::whereCode($code)->first();;
    }


    public function render()
    {
        return view('livewire.order.print')->layout('layouts.guest');
    }
}
