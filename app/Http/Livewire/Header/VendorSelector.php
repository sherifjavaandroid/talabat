<?php

namespace App\Http\Livewire\Header;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorManager;
use Illuminate\Support\Facades\Auth;

class VendorSelector extends BaseLivewireComponent
{

    public $vendors = [];
    public $vendor_id;


    protected $listeners = [];

    //
    public function mount()
    {
        $this->loadVendors();
    }

    public function render()
    {
        if (empty($this->vendors)) {
            $this->loadVendors();
        }
        return view('livewire.header.vendor_selector');
    }

    public function loadVendors()
    {
        $this->vendor_id = Auth::user()->vendor_id ?? "";
        $vendorManagerIds = VendorManager::where('user_id', Auth::id())->get()->pluck('vendor_id');
        $this->vendors = Vendor::whereIn('id', $vendorManagerIds)->get();
    }

    public function updatedVendorId($value)
    {
        try {
            \DB::beginTransaction();
            $user = User::find(Auth::id());
            $user->vendor_id = $value;
            $user->save();
            \DB::commit();
            $this->showSuccessAlert(__('Current vendor updated successfully!'));
            // return redirect(request()->header('Referer'));
            return redirect()->route('dashboard');
        } catch (\Exception $ex) {
            \DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __('Error updating current vendor'));
        }
    }
}