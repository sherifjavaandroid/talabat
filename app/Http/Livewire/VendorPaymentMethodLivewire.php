<?php

namespace App\Http\Livewire;

use App\Models\PaymentMethod;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\VendorPaymentMethod;
//use auth
use Illuminate\Support\Facades\Auth;

class VendorPaymentMethodLivewire extends BaseLivewireComponent
{

    //
    public $model = VendorPaymentMethod::class;
    public $vendorPaymentMethods = [];
    public $paymentMethods;

    public function mount()
    {
        $this->paymentMethods = PaymentMethod::active()->get();
    }

    public function render()
    {
        return view('livewire.vendor-payment-methods');
    }

    public function showCreateModal()
    {

        //
        $this->vendorPaymentMethods = [];
        $this->paymentMethods = PaymentMethod::active()->get();
        foreach ($this->paymentMethods as $key => $paymentMethod) {
            $vendorPaymentMethod = VendorPaymentMethod::where('vendor_id', Auth::user()->vendor_id)
                ->where('payment_method_id', $paymentMethod->id)
                ->first();
            //
            $this->vendorPaymentMethods[$key] = [
                'payment_method_id' => $paymentMethod->id,
                'selected' => $vendorPaymentMethod ? true : false,
                'allow_pickup' => $vendorPaymentMethod ? $vendorPaymentMethod->allow_pickup : $paymentMethod->allow_pickup
            ];
        }


        parent::showCreateModal();
    }


    public function assignPaymentMethods()
    {
        try {

            $vendorId = Auth::user()->vendor_id;
            DB::beginTransaction();
            //remove all vendor payment methods
            VendorPaymentMethod::where('vendor_id', $vendorId)->delete();


            //assigning

            foreach ($this->vendorPaymentMethods as $vendorPaymentMethodObject) {
                $selected = $vendorPaymentMethodObject['selected'] ?? false;
                if (!$selected) {
                    continue;
                }
                $vendorPaymentMethod = new VendorPaymentMethod();
                $vendorPaymentMethod->payment_method_id = $vendorPaymentMethodObject['payment_method_id'];
                $vendorPaymentMethod->allow_pickup = $vendorPaymentMethodObject['allow_pickup'];
                $vendorPaymentMethod->vendor_id = $vendorId;
                $vendorPaymentMethod->save();
            }

            DB::commit();
            $this->emit('refreshTable');
            $this->showSuccessAlert(__("Payment Methods") . " " . __("assigned successfully!"));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Payment Methods") . " " . __("assignment failed!"));
        }
    }
}
