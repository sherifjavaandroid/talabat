<?php

namespace App\Http\Livewire\Select;


use Illuminate\Support\Collection;
use App\Models\VendorPaymentMethod;
use App\Models\PaymentMethod;


class PaymentMethodSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $paymentMethods = [];
        $vendorId = $this->getDependingValue('vendor_id') ?? "";

        if (!empty($vendorId)) {
            $vendorPaymentMethodIds = VendorPaymentMethod::where('vendor_id', $vendorId)->get()->pluck('payment_method_id');
            if ($vendorPaymentMethodIds->count() > 0) {
                $paymentMethods = PaymentMethod::active()->whereIn('id', $vendorPaymentMethodIds)->get();
            } else {
                $paymentMethods = PaymentMethod::active()->get();
            }
        } else {
            $paymentMethods = PaymentMethod::active()->get();
        }

        //
        return   $paymentMethods
            ->map(function ($model) {
                return [
                    'value' => $model->id,
                    'description' => $model->name,
                ];
            });
    }


    public function selectedOption($value)
    {
        $model = PaymentMethod::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name,
        ];
    }
}
