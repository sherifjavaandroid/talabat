<?php

namespace App\Http\Livewire;

use App\Models\PaymentAccount;
use App\Models\Payout;
use App\Models\Vendor;
use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyPayoutLivewire extends BaseLivewireComponent
{

    public $model = Payout::class;

    //
    public $paymentAccounts = [];
    public $amount;
    public $note;
    public $payment_account_id;

    public function render()
    {

        $this->paymentAccounts = PaymentAccount::whereHasMorph('accountable', [Vendor::class], function ($query) {
            return $query->where("id", Auth::user()->vendor_id);
        })->get();
        $this->payment_account_id = $this->paymentAccounts->first()->id ?? "";

        return view('livewire.my_payouts', [
            "paymentAccounts" => $this->paymentAccounts,
        ]);
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->amount = $this->selectedModel->amount;
        $this->note = $this->selectedModel->note;
        $this->showEditModal();
    }

    public function requestPayout()
    {
        //
        $earning = Earning::firstOrCreate(
            ['vendor_id' =>  Auth::user()->vendor_id],
            ['amount' => 0.00]
        );
        //validate
        $this->validate(
            [
                "amount" => "required|numeric|max:" . $earning->amount ?? 0.00 . "",
            ]
        );

        try {

            //
            DB::beginTransaction();
            //new model
            $payout = new Payout();
            $payout->earning_id = $earning->id;
            $payout->amount = $this->amount;
            $payout->note = $this->note ?? '';
            $payout->payment_account_id = $this->payment_account_id;
            $payout->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Request Payout") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Request Payout") . " " . __('creation failed!'));
        }
    }
}