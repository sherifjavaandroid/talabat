<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;

class ProductDetailsLivewire extends BaseLivewireComponent
{

    //
    public $model = Product::class;
    public $ordersCount = 0;
    public $totalUnitSold = 0;
    public $totalPriceSold = 0;

    public function mount($id)
    {
        $this->selectedModel = Product::withTrashed()->find($id);

        //if auth is admin
        $user = User::find(\Auth::id());
        $iAMAdmin = $user->hasRole("admin");
        $isMyVendor = $user->vendor_id == $this->selectedModel->vendor_id;
        if (!$isMyVendor) {
            $canVendorVendors = $this->selectedModel->vendor->creator_id == $user->id;
            if (!$iAMAdmin && !$canVendorVendors) {
                abort(403, __("Unauthorized Access. Please try with an authorized credentials"));
            }
        }



        $rawQuery = OrderProduct::where("product_id", $id)
            ->whereHas('order', function ($query) {
                $query->where('payment_status', "successful")
                    ->currentStatus("delivered");
            });
        $orderRawQuery = Order::where('payment_status', "successful")
            ->currentStatus("delivered")
            ->whereHas('products', function ($query) use ($id) {
                return $query->where('product_id', $id);
            });

        $this->ordersCount = $orderRawQuery->count();
        $this->totalUnitSold = $rawQuery->sum('quantity');
        //let the total price sold be sum of price * quantity in one query
        $this->totalPriceSold = $rawQuery->selectRaw('sum(quantity * price) as total_unit_price')->get()->sum('total_unit_price');
    }

    public function render()
    {
        return view('livewire.product_details');
    }
}