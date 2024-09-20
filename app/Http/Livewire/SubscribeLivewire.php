<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;
use App\Traits\BillplzTrait;
use App\Traits\AbitmediaTrait;
use App\Traits\PaypalTrait;
use App\Traits\PayTmTrait;
use App\Traits\PayUTrait;

class SubscribeLivewire extends BaseLivewireComponent
{

    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait, BillplzTrait, AbitmediaTrait;
    use PaypalTrait, PayTmTrait, PayUTrait;
    //
    public $model = Subscription::class;

    //
    public $subscriptions;
    public $selectedSubscription;
    public $paymentMethods;
    public $selectedPaymentMethod;
    public $done = false;
    public $code;
    public $error;
    public $errorMessage;
    public $paymentCode;

    public function render()
    {
        $this->subscriptions = Subscription::active()->get();
        $this->paymentMethods = PaymentMethod::active()->sub()->get();
        return view('livewire.subscribe', [
            'paypalMethod' => PaymentMethod::where('slug', 'paypal')->first(),
        ]);
    }

    public function subscriptionSelected($id)
    {
        $this->selectedSubscription = Subscription::find($id);
        $this->showCreateModal();
    }

    public function initPayment($id)
    {
        $this->closeModal();
        $this->selectedPaymentMethod = PaymentMethod::find($id);
        $paymentMethodSlug = $this->selectedPaymentMethod->slug;
        $this->showEditModal();

        if ($paymentMethodSlug == "stripe") {
            //
            $session = $this->createStripeSubscribeSession(
                $this->selectedSubscription,
                $this->selectedPaymentMethod
            );
            $this->emit('initStripe', [
                $this->selectedPaymentMethod->public_key,
                $session,
            ]);
        } else if ($paymentMethodSlug == "razorpay") {
            //initialize razorpay payment order
            $razorpayOrderData = $this->createRazorpaySubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            //
            $this->emit('initRazorpay', [
                $this->selectedPaymentMethod->public_key,
                $this->selectedSubscription->amount * 100,
                setting('currencyCode', 'INR'),
                setting('websiteName', env("APP_NAME")),
                setting('websiteLogo', asset('images/logo.png')),
                $razorpayOrderData[1],
                //callback url
                route('api.subscription.callback', ["code" => $razorpayOrderData[0], "status" => "success"]),
                //redirect url
                route('subscription.callback', ["code" => $razorpayOrderData[0], "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "paystack") {
            //initialize razorpay payment order
            $paymentRef = $this->createPaystackSubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            //
            $this->emit('initPaystack', [
                $this->selectedPaymentMethod->public_key,
                \Auth::user()->email,
                $this->selectedSubscription->amount * 100,
                setting('currencyCode', 'USD'),
                $paymentRef,
                route('subscription.callback', ["code" => $paymentRef, "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "flutterwave") {
            //initialize razorpay payment order
            $paymentRef = $this->createFlutterwaveSubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            //
            $this->emit('initFlwPayment', [
                $this->selectedPaymentMethod->public_key,
                $paymentRef,
                $this->selectedSubscription->amount,
                setting('currencyCode', 'USD'),
                //country code
                setting('currencyCountryCode', 'US'),
                route('subscription.callback', ["code" => $paymentRef, "status" => "success"]),
                //customer info
                [
                    \Auth::user()->email,
                    \Auth::user()->phone,
                    \Auth::user()->name,
                ],
                //company info
                [
                    setting('websiteName', env("APP_NAME")),
                    setting('websiteLogo', asset('images/logo.png')),
                ],
            ]);
        } else if ($paymentMethodSlug == "billplz") {
            //initialize billplz payment order
            $paymentLink = $this->createBillplzSubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            return redirect()->away($paymentLink);
        } else if ($paymentMethodSlug == "abitmedia") {
            //initialize razorpay payment order
            $paymentLink = $this->createAbitmediaSubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            return redirect()->away($paymentLink);
        } else if ($paymentMethodSlug == "paypal") {
            //initialize paypal payment order
            $vendorSubscription = new SubscriptionVendor();
            $vendorSubscription->code = \Str::random(12);
            $vendorSubscription->status = "pending";
            $vendorSubscription->payment_method_id = $this->selectedPaymentMethod->id;
            $vendorSubscription->subscription_id = $this->selectedSubscription->id;
            $vendorSubscription->vendor_id = \Auth::user()->vendor_id;
            $vendorSubscription->save();

            $this->emit('initPaypalPayment', [
                $this->selectedSubscription->amount,
                setting('currencyCode', 'USD'),
                route('subscription.callback', ["code" => $vendorSubscription->code, "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "paytm") {
            //initialize paytm payment order
            $response = $this->createPayTmSubscribeReference($this->selectedSubscription, $this->selectedPaymentMethod);
            $paymentData = $response["params"];
            $paymentData["CHECKSUMHASH"] = $response["checkSum"];
            //
            $this->emit('initPayTmPayment', $paymentData);
        } else if ($paymentMethodSlug == "payu") {
            //initialize payu payment order
            $paymentData = $this->createPayUSubscribeReference($this->selectedModel, $this->selectedPaymentMethod);
            $this->emit('initPayUPayment', $paymentData);
        } else if ($paymentMethodSlug == "wallet") {
            //check the wallet balance of the current user
            $managerWallet = Wallet::firstOrCreate(["user_id" => \Auth::id()], ["balance" => 0]);
            $walletBalance = $managerWallet->balance;
            //check if the wallet balance is enough
            if ($walletBalance >= $this->selectedSubscription->amount) {
                $this->processWalletPayment($managerWallet);
            } else {
                //
                $this->closeModal();
                $this->showCreateModal();
                $this->errorMessage = __("Insufficient wallet balance. Please top up your wallet");
                $this->showErrorAlert($this->errorMessage);
                return;
            }
        }
        //custom payment
    }

    public function saveOfflinePayment()
    {

        $this->validate(
            [
                "paymentCode" => "required",
                "photo" => "required|image|max:4096",
            ]
        );


        try {

            \DB::beginTransaction();
            $this->selectedModel = new SubscriptionVendor();
            $this->selectedModel->code = $this->paymentCode;
            //payment status
            $this->selectedModel->status = "review";
            $this->selectedModel->payment_method_id = $this->selectedPaymentMethod->id;
            $this->selectedModel->subscription_id = $this->selectedSubscription->id;
            $this->selectedModel->vendor_id = \Auth::user()->vendor_id;
            $this->selectedModel->save();

            if ($this->photo) {
                $this->selectedModel->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            \DB::commit();
            $this->error = false;
            $this->errorMessage = __("Payment info uploaded successfully. You will be notified once approved");
        } catch (Exception $error) {
            \DB::rollback();
            $this->error = true;
            $this->errorMessage = $error->getMessage() ?? __("Payment info uploaded failed!");
        }

        $this->done = true;
    }


    public function processWalletPayment($managerWallet)
    {
        try {
            \DB::beginTransaction();
            //deduct the wallet balance
            $managerWallet->balance -= $this->selectedSubscription->amount;
            $managerWallet->save();
            //create a wallet transaction
            $walletTransaction = new WalletTransaction();
            $walletTransaction->ref = "sub_" . \Str::random(12);
            $walletTransaction->wallet_id = $managerWallet->id;
            $walletTransaction->amount = $this->selectedSubscription->amount;
            $walletTransaction->is_credit = false;
            $walletTransaction->reason = __("Subscription payment for :subscription", ["subscription" => $this->selectedSubscription->name]);
            $walletTransaction->save();
            //create a subscription vendor
            $this->selectedModel = new SubscriptionVendor();
            $this->selectedModel->code = \Str::random(12);
            $this->selectedModel->status = "successful";
            $this->selectedModel->payment_method_id = $this->selectedPaymentMethod->id;
            $this->selectedModel->subscription_id = $this->selectedSubscription->id;
            $this->selectedModel->vendor_id = \Auth::user()->vendor_id;
            $this->selectedModel->expires_at = now()->addDays($this->selectedSubscription->days);
            $this->selectedModel->saveQuietly();
            \DB::commit();
            $this->dismissModal();
            //send a success message
            $this->showSuccessAlert(__("Subscription payment successful. Please refresh the page"));
            //redirect to the subscription page
            $route = route('my.subscriptions');
            return redirect($route);
        } catch (Exception $error) {
            logger("Wallet payment error", [$error]);
            \DB::rollback();
            $this->closeModal();
            $this->showCreateModal();
            $this->errorMessage = $error->getMessage() ?? __("Subscription payment failed");
            $this->showErrorAlert($this->errorMessage);
        }
    }
}