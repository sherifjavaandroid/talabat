<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\SmsGateway;
use Aloha\Twilio\Twilio;
use App\Services\OTPService;

class SMSGatewayLivewire extends BaseLivewireComponent
{

    //
    public $model = SmsGateway::class;

    //
    public $name;
    public $isActive;

    //
    public $accountId;
    public $token;
    public $fromNumber;
    //
    public $authkey;
    public $sender;
    public $route;
    public $authSecret;
    public $template_id;
    public $template;


    //testing
    public $phoneNumber;
    public $testMessage;


    protected $rules = [
        "name" => "required|string",
    ];


    public function render()
    {
        return view('livewire.sms-gateways');
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->isActive = $this->selectedModel->is_active;

        //
        if ($this->selectedModel->slug == "twilio") {
            $this->accountId = env("TWILIO_ACCOUNT_SID");
            $this->token = env("TWILIO_AUTH_TOKEN");
            $this->fromNumber = env("TWILIO_FROM");
        } else if ($this->selectedModel->slug == "msg91") {
            $this->authkey = env("MSG91_AUTHKEY");
            $this->template_id = env("MSG91_TEMPLATE_ID");
            $this->sender = env("MSG91_SENDER");
            $this->route = env("MSG91_ROUTE");
            $this->template = env("MSG91_TEMPLATE");
        } else if ($this->selectedModel->slug == "gatewayapi") {
            $this->authkey = env("GATEWAYAPI_AUTHKEY");
            $this->sender = env("GATEWAYAPI_SENDER");
            $this->authSecret = env("GATEWAYAPI_AUTHSECRET");
            $this->token = env("GATEWAYAPI_TOKEN");
        } else if ($this->selectedModel->slug == "termii") {
            $this->authkey = env("TERMII_AUTHKEY");
            $this->sender = env("TERMII_SENDER");
        } else if ($this->selectedModel->slug == "africastalking") {
            $this->authkey = env("AFRICASTALKING_AUTHKEY");
            $this->token = env("AFRICASTALKING_TOKEN");
            $this->sender = env("AFRICASTALKING_SENDER");
        } else if ($this->selectedModel->slug == "hubtel") {
            $this->authkey = env("HUBTEL_AUTHKEY");
            $this->token = env("HUBTEL_TOKEN");
            $this->sender = env("HUBTEL_SENDER");
        }
        //edit custom code
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->is_active = $this->isActive;
            $model->save();


            //
            if ($this->selectedModel->slug == "twilio") {
                setEnv("TWILIO_ACCOUNT_SID", $this->accountId, "TWILIO");
                setEnv("TWILIO_AUTH_TOKEN", $this->token, "TWILIO");
                setEnv("TWILIO_FROM", $this->fromNumber, "TWILIO");
            } else if ($this->selectedModel->slug == "msg91") {
                $group = "MSG91";
                setEnv("MSG91_AUTHKEY", $this->authkey, $group);
                setEnv("MSG91_TEMPLATE_ID", $this->template_id, $group);
                setEnv("MSG91_SENDER", $this->sender, $group);
                setEnv("MSG91_ROUTE", $this->route, $group);
                setEnv("MSG91_TEMPLATE", "{$this->template}", $group);
            } else if ($this->selectedModel->slug == "gatewayapi") {
                //
                $group = "GATEWAYAPI";
                setEnv("GATEWAYAPI_AUTHKEY", $this->authkey, $group);
                setEnv("GATEWAYAPI_SENDER", $this->sender, $group);
                setEnv("GATEWAYAPI_AUTHSECRET", $this->authSecret, $group);
                setEnv("GATEWAYAPI_TOKEN", $this->token, $group);
            } else if ($this->selectedModel->slug == "termii") {

                $group = "TERMII";
                setEnv("TERMII_AUTHKEY", $this->authkey, $group);
                setEnv("TERMII_SENDER", $this->sender, $group);
            } else if ($this->selectedModel->slug == "africastalking") {

                $group = "AFRICASTALKING";
                setEnv("AFRICASTALKING_AUTHKEY", $this->authkey, $group);
                setEnv("AFRICASTALKING_SENDER", $this->sender, $group);
                setEnv("AFRICASTALKING_TOKEN", $this->token, $group);
                //
            } else if ($this->selectedModel->slug == "hubtel") {
                $group = "HUBTEL";
                setEnv("HUBTEL_AUTHKEY", $this->authkey, $group);
                setEnv("HUBTEL_TOKEN", $this->token, $group);
                setEnv("HUBTEL_SENDER", $this->sender, $group);
            }
            //custom code

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Sms Gateway") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Sms Gateway") . " " . __('creation failed!'));
        }
    }



    public function testSMS()
    {
        if ($this->inDemo()) {
            $this->showErrorAlert(__("This action is not allowed in demo mode"));
            return;
        }

        //validate
        $this->validate([
            "phoneNumber" => "required|phone:" . setting('countryCode', "GH") . "",
            "testMessage" => "required|string",
        ]);


        //
        if ($this->selectedModel->slug == "twilio") {
            $accountId = env("TWILIO_ACCOUNT_SID");
            $token = env("TWILIO_AUTH_TOKEN");
            $fromNumber = env("TWILIO_FROM");
            //
            $twilio = new Twilio($accountId, $token, $fromNumber);
            //send sms
            try {
                $twilio->message($this->phoneNumber, $this->testMessage);
                $this->showSuccessAlert("SMS sent successfully");
            } catch (\Exception $ex) {
                $this->showErrorAlert($ex->getMessage() ?? "SMS Failed to send");
            }
            // } else if (in_array($this->selectedModel->slug, ["msg91", "gatewayapi", "termii", ])) {
        } else {

            //send sms
            try {
                $otpService = new OTPService();
                $otpService->sendOTP($this->phoneNumber, $this->testMessage, $gateway = $this->selectedModel->slug);
                $this->showSuccessAlert("SMS sent successfully");
            } catch (\Exception $ex) {
                $this->showErrorAlert($ex->getMessage() ?? "SMS Failed to send");
            }
        }

        //
    }
}
