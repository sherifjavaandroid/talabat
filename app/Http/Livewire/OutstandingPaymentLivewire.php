<?php

namespace App\Http\Livewire;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class OutstandingPaymentLivewire extends BaseLivewireComponent
{

    //
    public $model = WalletTransaction::class;
    public $user_id;
    public $wallet;
    public $actions = [
        "credit" => "Credit",
        "debit" => "Debit",
    ];
    public $selectedAction = "credit";
    public $amount;
    public $note;



    public function mount()
    {
        \Cache::put('wallet_current_url', route('wallet.transactions'));
    }

    public function render()
    {
        return view('livewire.outstanding-payments');
    }


    public function autocompleteUserSelected($user)
    {
        $this->wallet = Wallet::firstOrCreate([
            "user_id" => $user['id']
        ],[
            "balance" => 0.00,
        ]);
        $this->user_id = $user['id'];
    }


    public function save()
    {

        $this->validate(
            [
                "user_id" => "required|exists:users,id",
                "selectedAction" => "required",
                "amount" => "required|numeric",
            ]
        );


        try {

            DB::beginTransaction();

            if ($this->selectedAction == "credit") {
                $this->wallet->balance += $this->amount;
            } else {
                $this->wallet->balance -= $this->amount;
            }
            $this->wallet->save();
            $walletTransaction = new WalletTransaction();
            $walletTransaction->wallet_id = $this->wallet->id;
            $walletTransaction->amount = $this->amount;
            $walletTransaction->is_credit = $this->selectedAction == "credit";
            $walletTransaction->reason = $this->note;
            $walletTransaction->status = "successful";
            $walletTransaction->save();

            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Wallet") . " " . __('updated successfully!'));
            $this->emit('clearAutocompleteFieldsEvent');
            $this->emit('refreshTable');

        } catch (\Exception $ex) {
            DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __('Failed'));
        }
    }
}
