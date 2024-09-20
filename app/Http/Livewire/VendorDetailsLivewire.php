<?php

namespace App\Http\Livewire;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Order;

class VendorDetailsLivewire extends BaseLivewireComponent
{

    //
    public $model = Vendor::class;
    public $ordersCount = 0;
    public $totalPriceSold = 0;

    public function mount($id)
    {
        //throw error if auth is not admin and not vendor creator or assign to the vendor
        $this->selectedModel = Vendor::withTrashed()->find($id);
        //if auth is admin
        $user = User::find(\Auth::id());
        $iAMAdmin = $user->hasRole("admin");
        $canVendorVendors = $user->checkPermissionTo("view-vendors");
        $isMyVendor = $user->vendor_id == $this->selectedModel->id;
        if (!$isMyVendor) {
            if (!$iAMAdmin && !$canVendorVendors) {
                abort(403, __("Unauthorized Access. Please try with an authorized credentials"));
            }
        }

        $rawQuery = Order::where("vendor_id", $id)
            ->where('payment_status', "successful")
            ->currentStatus("delivered");
        //
        $this->ordersCount = $rawQuery->count();
        $this->totalPriceSold = $rawQuery->sum('total');
    }

    public function render()
    {
        return view('livewire.vendor_details');
    }
}
