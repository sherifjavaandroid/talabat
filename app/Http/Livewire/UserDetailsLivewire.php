<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Order;
use App\Models\LoyaltyPoint;

class UserDetailsLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;
    public $ordersCount = 0;
    public $loyaltyPoints = 0;
    public $expensiveOrders = 0;
    public $prevUserId;
    public $nextUserId;

    public function mount($id)
    {
        $this->selectedModel = User::withTrashed()->find($id);
        $this->ordersCount = Order::whereUserId($id)->count();
        $this->expensiveOrders = Order::whereUserId($id)->orderBy('total', 'DESC')->first()->total ?? 0;
        $this->prevUserId = User::where('id', '<', $this->selectedModel->id)->max('id');
        $this->nextUserId = User::where('id', '>', $this->selectedModel->id)->min('id');
        $this->loyaltyPoints = LoyaltyPoint::whereUserId($id)->first()->points ?? 0;
    }

    public function render()
    {
        return view('livewire.user_details');
    }
}