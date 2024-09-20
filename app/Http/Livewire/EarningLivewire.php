<?php

namespace App\Http\Livewire;

use App\Models\Earning;
use App\Models\PaymentAccount;
use App\Models\PaymentMethod;
use App\Models\Payout;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EarningLivewire extends BaseLivewireComponent
{

    //
    public $model = Earning::class;

    //
    public $amount;
    public $password;
    public $payment_method_id;
    public $payment_account_id;
    public $note;
    public $type;
    public $transferAmount;
    protected $queryString = ['type'];
    public $paymentAccounts;

    public function render()
    {
        $paymentMethods = PaymentMethod::active()->get();
        $this->payment_method_id = $paymentMethods->first()->id;
        return view('livewire.earnings', [
            "paymentMethods" => $paymentMethods
        ]);
    }


    public function initiatePayout($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->paymentAccounts = PaymentAccount::where('accountable_id', Auth::id())
            ->where('accountable_type', 'App\Models\User')
            ->where('is_active', true)
            ->get();
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
            $payout->payment_account_id = $this->payment_account_id ?? $this->paymentAccounts->first()->id ?? null;
            $payout->user_id = Auth::id();
            $payout->amount = (float)$this->amount;
            $payout->note = $this->note;
            $payout->status = "successful";
            $payout->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Payout") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Payout") . " " . __('creation failed!'));
        }
    }
}
