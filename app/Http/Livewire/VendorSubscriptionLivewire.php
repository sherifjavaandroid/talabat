<?php

namespace App\Http\Livewire;

use App\Models\SubscriptionVendor;
use Illuminate\Support\Facades\DB;
use Exception;

class VendorSubscriptionLivewire extends BaseLivewireComponent
{

    public $selectedVendorSubscription;

    public function render()
    {
        return view('livewire.vendor_subscriptions');
    }

    public function reviewPayment($id)
    {
        $this->selectedVendorSubscription = SubscriptionVendor::find($id);
        $this->showCreateModal();
    }

    public function initiateActivate()
    {

        try {

            DB::beginTransaction();
            $this->selectedVendorSubscription->status = "successful";
            $this->selectedVendorSubscription->save();
            $this->dismissModal();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vendor Subscription payment approved"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("User Create Error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor Subscription payment failed"));
        }
    }
    public function initiateDeactivate()
    {

        try {

            DB::beginTransaction();
            $this->selectedVendorSubscription->status = "failed";
            $this->selectedVendorSubscription->save();
            $this->dismissModal();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vendor Subscription payment rejected successful"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("User Create Error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor Subscription payment rejection failed"));
        }
    }
}
