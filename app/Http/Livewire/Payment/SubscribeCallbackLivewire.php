<?php

namespace App\Http\Livewire\Payment;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\SubscriptionVendor;
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

class SubscribeCallbackLivewire extends BaseLivewireComponent
{

    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait, BillplzTrait, AbitmediaTrait;
    use PaypalTrait, PayTmTrait, PayUTrait;


    public $code;
    public $status;
    public $hash;
    public $rep_status;
    public $transaction_id;
    public $error;
    public $errorMessage;
    protected $queryString = ['code', 'status', 'transaction_id','hash','rep_status'];


    public function mount()
    {
        $this->selectedModel = SubscriptionVendor::with('vendor', 'subscription')->where('code', $this->code)->first();
        //
        if (empty($this->selectedModel)) {
        } else {

            try {
                if ($this->selectedModel->payment_method->slug == "stripe") {
                    $this->verifyStripeSubscriptionTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "razorpay") {
                    $this->verifyRazorpaySubscriptionTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "paystack") {
                    $this->verifyPaystackSubscriptionTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "flutterwave") {
                    $this->verifyFlutterwaveSubscriptionTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "billplz") {
                    $this->verifyBillplzSubscriptionTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "abitmedia") {
                    $this->verifyAbitmediaSubscriptionTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "paypal") {
                    $this->verifyPaypalSubscriptionTransaction($this->selectedModel, $this->transaction_id);
                } else if ($this->selectedModel->payment_method->slug == "paytm") {
                    $this->verifyPayTmSubscriptionTransaction($this->selectedModel);
                }else if ($this->selectedModel->payment_method->slug == "payu") {
                    $this->verifyPayUSubscriptionTransaction($this->selectedModel, $this->hash, $this->rep_status);
                }
                //custom payment
                $this->error = false;
            } catch (\Exception $ex) {
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

            return view('livewire.payment.subscription_callback')->layout('layouts.guest');
        }
    }
}
