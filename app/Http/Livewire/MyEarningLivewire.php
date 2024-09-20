<?php

namespace App\Http\Livewire;

use App\Models\Earning;
use App\Models\PaymentMethod;
use App\Models\Payout;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyEarningLivewire extends BaseLivewireComponent
{

    //
    public $model = Earning::class;

    //
    public $earning;
    public $amount;
    public $payment_method_id;
    public $note;
    public $type;
    public $paymentMethods;

    public function render()
    {
        if (empty($this->paymentMethods)) {
            $this->paymentMethods = PaymentMethod::active()->get();
        }
        if (empty($this->payment_method_id)) {
            $this->payment_method_id = $this->paymentMethods->first()->id;
        }

        if(empty($this->earning)){
            $this->earning = Earning::where('vendor_id', \Auth::user()->vendor_id)->first();
        }
        return view('livewire.my_earnings');
    }


    public function initiatePayout($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->emit('showCreateModal');
    }

    public function payout()
    {
        //validate
        $this->validate(
            [
                "amount" => "required|numeric|max:" . $this->selectedModel->amount . "",
            ]
        );

        try {

            DB::beginTransaction();
            $payout = new Payout();
            $payout->earning_id = $this->selectedModel->id;
            $payout->payment_method_id = $this->payment_method_id;
            $payout->user_id = Auth::id();
            $payout->amount = (float)$this->amount;
            $payout->note = $this->note;
            $payout->save();
            DB::commit();

            $this->dismissModal();
            $this->reset(
                [
                    "amount",
                    "payment_method_id",
                    "note"
                ]
            );
            $this->showSuccessAlert(__("Payout") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Payout") . " " . __('creation failed!'));
        }
    }
}
