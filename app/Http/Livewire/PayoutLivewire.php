<?php

namespace App\Http\Livewire;

use App\Models\Payout;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayoutLivewire extends BaseLivewireComponent
{

    public $model = Payout::class;
    public $type;
    public $payment_method_id;
    protected $queryString = ['type'];

    //
    public $amount;
    public $note;

    public function mount()
    {
        if ($this->type != "drivers" && !Auth::user()->hasAnyRole('admin', 'city-admin')) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::active()->get();
        $this->payment_method_id = $paymentMethods->first()->id;
        return view('livewire.payouts', [
            "paymentMethods" => $paymentMethods
        ]);
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->amount = $this->selectedModel->amount;
        $this->showEditModal();
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
            $payout = $this->selectedModel;
            $payout->payment_method_id = $this->payment_method_id;
            $payout->user_id = Auth::id();
            $payout->amount = (float)$this->amount;
            $payout->note = $this->note;
            $payout->status = "successful";
            $payout->save();
            DB::commit();

            $this->dismissModal();
            $type = $this->type;
            $this->reset();
            $this->type = $type;
            $this->showSuccessAlert(__("Payout") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Payout") . " " . __('creation failed!'));
        }
    }
}