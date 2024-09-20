<?php

namespace App\Http\Livewire;

use App\Models\Earning;
use App\Models\PaymentMethod;
use App\Models\Payout;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DriverEarningLivewire extends BaseLivewireComponent
{

    //
    public $model = Earning::class;

    //
    public $amount;
    public $payment_method_id;
    public $note;
    public $type;

    public $transferAmount;
    public $password;

    public function getListeners()
    {
        return $this->listeners + [
            "initiateEarningWalletClearance" => "initiateEarningWalletClearance",
            "processEarningWalletClearance" => "processEarningWalletClearance",
        ];
    }


    public function render()
    {

        $this->type = "drivers";
        $paymentMethods = PaymentMethod::active()->get();
        $this->payment_method_id = $paymentMethods->first()->id;
        return view('livewire.earnings', [
            "paymentMethods" => $paymentMethods
        ]);
    }


    public function initiatePayout($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->emit('showCreateModal');
    }

    public function initiateEarningWalletTransfer($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->emit('showEditModal');
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
            $this->reset();
            $this->showSuccessAlert(__("Payout") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Payout") . " " . __('creation failed!'));
        }
    }

    public function transfer()
    {
        //validate
        $this->validate(
            [
                "transferAmount" => "required|numeric|max:" . $this->selectedModel->amount . "",
                "password" => "required",
            ]
        );

        try {

            //verify if the payment is correct
            if (!\Hash::check($this->password, \Auth::user()->password)) {
                throw new Exception(__("Invalid account password"));
            }

            DB::beginTransaction();
            //get user wallet
            $userWallet = $this->selectedModel->user->wallet;
            if (empty($userWallet)) {
                $userWallet = $this->selectedModel->user->updateWallet(0);
            }
            //transfer to wallet
            $newBalance = $userWallet->balance + $this->transferAmount;
            $this->selectedModel->user->updateWallet($newBalance);

            //reduce the earning amount
            $this->selectedModel->amount -= $this->transferAmount;
            $this->selectedModel->save();


            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Wallet Transfer") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Wallet Transfer") . " " . __('creation failed!'));
        }
    }


    //
    public function initiateEarningWalletClearance($id)
    {
        $this->selectedModel = $this->model::find($id);
        //show warning modal
        $this->alert('warning', "", [
            'position'  =>  'center',
            "title" => __("Clear Earning"),
            'text' => __("Are you sure you want to clear this earning?"),
            'toast'  =>  false,
            'showConfirmButton'  =>  true,
            'cancelButtonText' => __('Cancel'),
            "confirmButtonText" => __("Yes, Clear"),
            'onConfirmed' => 'processEarningWalletClearance',
        ]);
    }

    //
    public function processEarningWalletClearance()
    {
        try {
            $this->isDemo();
            DB::beginTransaction();
            //reduce the earning amount
            $this->selectedModel->amount = 0;
            $this->selectedModel->save();
            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Earning Clearance successfully!"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Earning Clearance failed!"));
        }
    }
}