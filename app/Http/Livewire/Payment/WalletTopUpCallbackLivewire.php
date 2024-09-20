<?php

namespace App\Http\Livewire\Payment;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\WalletTransaction;
use App\Traits\AbitmediaTrait;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;
use App\Traits\BillplzTrait;
use App\Traits\PaypalTrait;
use App\Traits\PayTmTrait;
use App\Traits\PayUTrait;

class WalletTopUpCallbackLivewire extends BaseLivewireComponent
{

    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait, BillplzTrait, AbitmediaTrait;
    use PaypalTrait, PayTmTrait, PayUTrait;


    public $code;
    public $status;
    public $transaction_id;
    public $hash;
    public $rep_status;
    public $error;
    public $errorMessage;
    protected $queryString = ['code', 'status', 'transaction_id', 'hash', 'rep_status'];


    public function mount()
    {
        $this->selectedModel = WalletTransaction::with('wallet.user', 'payment_method')->where('ref', $this->code)->first();
        //
        if (empty($this->selectedModel)) {
        } else {

            try {
                if ($this->selectedModel->payment_method->slug == "stripe") {
                    $this->verifyStripeTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "razorpay") {
                    $this->verifyRazorpayTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "paystack") {
                    $this->verifyPaystackTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "flutterwave") {
                    $this->verifyFlutterwaveTopupTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "billplz") {
                    $this->verifyBillplzTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "abitmedia") {
                    $this->verifyAbitmediaTopupTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "paypal") {
                    $this->verifyPaypalTopupTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "paytm") {
                    $this->verifyPayTmTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "payu") {
                    $this->verifyPayUTopupTransaction($this->selectedModel, $this->hash, $this->rep_status);
                }
                //custom payment
                $this->error = false;
            } catch (\Exception $ex) {
                logger("Wallet topup callback Error", [$ex]);
                $this->error = true;
                $this->errorMessage = $ex->getMessage();
            }
        }
    }

    public function render()
    {

        //
        if (empty($this->selectedModel)) {
            return view('livewire.payment.invalid')->layout('layouts.guest');
        } else {

            return view('livewire.payment.wallet_callback')->layout('layouts.guest');
        }
    }
}
