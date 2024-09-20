<?php

namespace App\Http\Livewire;

use App\Models\PaymentMethod;
use Exception;

class WebhookSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $prefix;
    public $android;
    public $ios;


    public $rules = [
        "prefix" => "required|url"
    ];

    public function getListeners(){
        return $this->listeners + [
            "generateHash" => "generateHash"
        ];
    }

    public function mount()
    {
        //add missing column
        if (!\Schema::hasColumn('payment_methods', 'webhook_hash')) {
            \Schema::table("payment_methods", function ($table) {
                $table->string('webhook_hash')->nullable()->after('hash_key');
            });
        }

        //
        $paymentMethods = PaymentMethod::whereNull('webhook_hash')->get();
        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethod->webhook_hash = \Str::random(32);
            $paymentMethod->save();
        }
    }

    public function render()
    {
        return view('livewire.settings.webhook_settings');
    }


    public function generateHash($id)
    {


        try {

            $this->isDemo();
            $paymentMethod = PaymentMethod::find($id);
            $paymentMethod->webhook_hash = \Str::random(32);
            $paymentMethod->save();
            $this->showSuccessAlert(__("Hash re-generated successfully!"));
            $this->refreshDataTable();
        } catch (Exception $error) {
            logger("error", [$error]);
            $this->showErrorAlert($error->getMessage() ?? __("Hash re-generated failed!"));
        }
    }
}
