<?php

namespace App\Http\Livewire;

use App\Models\PaymentAccount;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class PaymentAccountLivewire extends BaseLivewireComponent
{

    //
    public $model = PaymentAccount::class;
    public $showNew = false;
    public $name;
    public $number;
    public $instructions;
    public $is_active;


    public $rules = [
        "name" => "required",
        "number" => "required|numeric",
        "instructions" => "sometimes",
        "is_active" => "sometimes",
    ];

    public function showNewBtn()
    {
        $authUser = \Auth::user();
        //
        if ($authUser->hasRole('manager')) {
            $this->showNew = true;
        }
    }
    public function render()
    {
        $this->showNewBtn();
        return view('livewire.payment-accounts');
    }


    public function save()
    {

        //
        $data = $this->validate();
        //
        try {
            //
            DB::beginTransaction();
            //new model
            $paymentAccount = new PaymentAccount();
            $paymentAccount->fill($data);

            //
            $authUser = \Auth::user();
            //if this a vendor manager 
            if ($authUser->hasRole('manager')) {
                //
                $vendor = Vendor::find($authUser->vendor_id);
                $paymentAccount->accountable()->associate($vendor)->save();
            } else {
                //
                $paymentAccount->accountable()->associate($authUser)->save();
            }

            //
            $paymentAccount->save();

            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Payment Account") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Payment Account") . " " . __('creation failed!'));
        }
    }


    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->number = $this->selectedModel->number;
        $this->instructions = $this->selectedModel->instructions;
        $this->is_active = $this->selectedModel->is_active;
        $this->emit('showEditModal');
    }


    public function update()
    {

        //
        $data = $this->validate();
        //
        try {
            //
            DB::beginTransaction();
            //new model
            $paymentAccount = $this->selectedModel;
            $paymentAccount->update($data);

            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Payment Account") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Payment Account") . " " . __('creation failed!'));
        }
    }
}
