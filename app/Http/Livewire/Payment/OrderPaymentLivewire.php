<?php

namespace App\Http\Livewire\Payment;

use App\Models\Order;
use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;
use App\Traits\BillplzTrait;
use App\Traits\AbitmediaTrait;
use App\Traits\PaypalTrait;
use App\Traits\PayTmTrait;
use App\Traits\PayUTrait;
use Exception;

class OrderPaymentLivewire extends BaseLivewireComponent
{
    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait, BillplzTrait, AbitmediaTrait;
    use PaypalTrait, PayTmTrait, PayUTrait;

    public $code;
    public $error;
    public $errorMessage;
    public $done = false;
    public $currency;
    public $paymentStatus;
    protected $queryString = ['code'];
    //
    public $paymentCode;
    public $customView;
    public $paymentMethods = [];


    public function mount()
    {
        $this->selectedModel = Order::where('code', $this->code)->first();
        $this->paymentStatus = $this->selectedModel->payment_status ?? "";

        //
        if (!empty($this->selectedModel) && empty($this->selectedModel->payment_method_id) && empty($this->paymentMethods)) {
            $this->paymentMethods = $this->selectedModel->vendor->payment_methods;
            if (empty($this->paymentMethods) || $this->paymentMethods->count() == 0) {
                $this->paymentMethods = PaymentMethod::active()->get();
            }
        }
    }

    public function render()
    {

        //
        if (empty($this->selectedModel)) {
            return view('livewire.payment.invalid')->layout('layouts.auth');
        } else if (!in_array($this->paymentStatus, ['pending', 'review'])) {
            // return view('livewire.payment.processed')->layout('layouts.auth');
            //payment already processed
            $link = route('payment.processed', ["code" => $this->selectedModel->code, "type" => "order"]);
            return redirect()->away($link);
        } else {
            return view('livewire.payment.order', [
                "order" => $this->selectedModel,
            ])->layout('layouts.guest');
        }
    }

    public function setPaymentMethod($id)
    {
        $this->selectedModel->payment_method_id = $id;
        $this->selectedModel->saveQuietly();
        //reload page
        $this->emit('reloadPage');
    }


    //
    public function initPayment()
    {
        // //remove delivery fee from the total to be processed
        // if ((bool) setting('finance.delivery.collectDeliveryCash', 0)) {
        //     $this->selectedModel->total = $this->selectedModel->total - $this->selectedModel->delivery_fee;
        // }
        //
        $paymentMethodSlug = $this->selectedModel->payment_method->slug;

        if ($paymentMethodSlug == "stripe") {
            $session = $this->createStripePaymentSession($this->selectedModel);
            $this->emit('initStripe', [
                $this->selectedModel->payment_method->public_key,
                $session,
            ]);
        } else if ($paymentMethodSlug == "razorpay") {
            //initialize razorpay payment order
            $razorpayOrderId = $this->createRazorpayPaymentReference($this->selectedModel);
            //
            $this->emit('initRazorpay', [
                $this->selectedModel->payment_method->public_key,
                $this->selectedModel->payable_total * 100,
                $this->selectedModel->getCurrencyCode(),
                setting('websiteName', env("APP_NAME")),
                setting('websiteLogo', asset('images/logo.png')),
                $razorpayOrderId,
                //callback url
                route('api.payment.callback', ["code" => $this->selectedModel->code, "status" => "success"]),
                //redirect url
                route('payment.callback', ["code" => $this->selectedModel->code, "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "paystack") {
            //initialize razorpay payment order
            $paymentRef = $this->createPaystackPaymentReference($this->selectedModel);
            //
            $this->emit('initPaystack', [
                $this->selectedModel->payment_method->public_key,
                $this->selectedModel->user->email,
                $this->selectedModel->payable_total * 100,
                $this->selectedModel->getCurrencyCode(),
                $paymentRef,
                route('payment.callback', ["code" => $this->selectedModel->code, "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "flutterwave") {
            //initialize razorpay payment order
            $paymentRef = $this->createFlutterwavePaymentReference($this->selectedModel);
            //
            $this->emit('initFlwPayment', [
                $this->selectedModel->payment_method->public_key,
                $paymentRef,
                $this->selectedModel->payable_total,
                $this->selectedModel->getCurrencyCode(),
                //country code
                setting('currencyCountryCode', 'US'),
                route('payment.callback', ["code" => $this->selectedModel->code, "status" => "success"]),
                //customer info
                [
                    $this->selectedModel->user->email,
                    $this->selectedModel->user->phone,
                    $this->selectedModel->user->name,
                ],
                //company info
                [
                    setting('websiteName', env("APP_NAME")),
                    setting('websiteLogo', asset('images/logo.png')),
                ],
            ]);
        } else if ($paymentMethodSlug == "billplz") {
            //initialize razorpay payment order
            $paymentLink = $this->createBillplzPaymentReference($this->selectedModel);
            return redirect()->away($paymentLink);
        } else if ($paymentMethodSlug == "abitmedia") {
            //initialize abitmedia payment order
            $paymentLink = $this->createAbitmediaPaymentReference($this->selectedModel);
            return redirect()->away($paymentLink);
        } else if ($paymentMethodSlug == "paypal") {
            //initialize paypal payment order
            $this->emit('initPaypalPayment', [
                $this->selectedModel->payable_total,
                $this->selectedModel->getCurrencyCode(),
                route('payment.callback', ["code" => $this->selectedModel->code, "status" => "success"]),
            ]);
        } else if ($paymentMethodSlug == "paytm") {
            //initialize paytm payment order
            $response = $this->createPayTmPaymentReference($this->selectedModel);
            $paymentData = $response["params"];
            $paymentData["CHECKSUMHASH"] = $response["checkSum"];
            //
            $this->emit('initPayTmPayment', $paymentData);
        } else if ($paymentMethodSlug == "payu") {
            //initialize payu payment order
            $paymentData = $this->createPayUPaymentReference($this->selectedModel);
            $this->emit('initPayUPayment', $paymentData);
        }
        //custom payment
    }

    public function checkOfflinePayMobileView()
    {
        $this->emit(
            'openExternalBrowser',
            route('order.payment', ["code" => $this->selectedModel->code]),
        );
    }

    //
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
            $payment = Payment::where('order_id', $this->selectedModel->id)->where('status', '!=', 'successful')->first();
            $payment ??= new Payment();
            $payment->order_id = $this->selectedModel->id;
            $payment->ref = $this->paymentCode;
            $payment->status = "review";
            $payment->save();

            //order payment status
            $this->selectedModel->payment_status = "review";
            $this->selectedModel->save();

            if ($this->photo) {
                $payment->clearMediaCollection();
                $payment->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            \DB::commit();
            $this->errorMessage = __("Payment info uploaded successfully. You will be notified once approved");
            $this->error = false;
        } catch (Exception $error) {
            \DB::rollback();
            $this->error = true;
            $this->errorMessage = $error->getMessage() ?? __("Payment info uploaded failed!");
        }

        $this->done = true;
    }
}
