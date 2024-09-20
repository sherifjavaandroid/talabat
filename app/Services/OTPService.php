<?php

namespace App\Services;

use Aloha\Twilio\Twilio;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Http;
use Craftsys\Msg91\Client;
use Exception;

class OTPService
{
    public function __constuct()
    {
        //
    }


    //
    public function sendOTP($phone, $message, $gateway = null, $user = null, $code = null): ?String
    {
        $isDemo = !\App::environment('production');

        $enabledSmsGateway = setting('otpGateway', 'twilio');
        if ($gateway != null) {
            $enabledSmsGateway = $gateway;
        }

        //
        $enabledSmsGateway = \Str::lower($enabledSmsGateway);
        if ($isDemo) {
            logger("SMS Sending", [$phone, $message, $gateway, $enabledSmsGateway]);
        }

        //
        if ($enabledSmsGateway == "twilio") {
            $accountId = env("TWILIO_ACCOUNT_SID");
            $token = env("TWILIO_AUTH_TOKEN");
            $fromNumber = env("TWILIO_FROM");
            //
            $twilio = new Twilio($accountId, $token, $fromNumber);
            $twilio->message($phone, $message);
        } else if ($enabledSmsGateway == "gatewayapi") {

            //
            $sender = env("GATEWAYAPI_SENDER");
            $apiToken = env("GATEWAYAPI_TOKEN");


            //Send an SMS using Gatewayapi.com
            $url = "https://gatewayapi.com/rest/mtsms";

            //Set SMS recipients and content
            $recipients = [$phone];
            $json = [
                'sender' => '' . $sender . '',
                'message' => $message,
                'recipients' => [],
            ];
            foreach ($recipients as $msisdn) {
                $json['recipients'][] = ['msisdn' => $msisdn];
            }

            //Using the built-in 'curl' library
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_USERPWD, $apiToken . ":");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //get response
            $output = curl_exec($ch);
            $isError = 0;
            $errorMessage = true;
            logger("output", [$output]);
            //Print error if any
            if (curl_errno($ch)) {
                $isError = true;
                $errorMessage = curl_error($ch);
            }
            curl_close($ch);
            if ($isError) {
                throw new \Exception($errorMessage, 1);
            }
        } else if ($enabledSmsGateway == "msg91") {
            $authKey = env("MSG91_AUTHKEY");
            $sender = env("MSG91_SENDER");
            $routeNo = env("MSG91_ROUTE");
            $templateId = env("MSG91_TEMPLATE_ID");
            $templateMsg = env("MSG91_TEMPLATE", "##OTP## is your verification code");

            if ($isDemo) {
                logger("msg91 sending sms", [
                    $authKey, $sender, $routeNo, $templateId
                ]);
            }


            $messageParts = explode(":", $message);
            $varibale1 = trim($messageParts[1] ?? "12345");

            $msg91Client = new Client([
                "key" => $authKey
            ]);

            $otp = $msg91Client->otp($varibale1);
            $response = $otp->to($phone)
                ->from($sender) // sender id
                ->template($templateId) // template id for otps
                ->options(function (\Craftsys\Msg91\OTP\Options $options) use ($varibale1, $templateMsg) {
                    $options->digits(6)
                        ->otp($varibale1) // set the number of digits in generated otp
                        ->message($templateMsg) // custom template
                        ->expiresInMinutes(60); // set the expiry
                })
                ->send();

            if ($isDemo) {
                logger("msg91Client Response ", [$messageParts, $varibale1, $response->getData()]);
            }
        } else if ($enabledSmsGateway == "termii") {
            $authkey = env("TERMII_AUTHKEY");
            $sender = env("TERMII_SENDER");
            // $apiEndpoint = 'https://termii.com/api/sms/send?to=' . $phone . '&from=' . $sender . '&sms=' . $message . '&type=plain&channel=generic&api_key=' . $authkey . '';
            $apiEndpoint = 'https://api.ng.termii.com/api/sms/send';
            $payload = [
                "api_key" => $authkey,
                "to" => $phone,
                "from" => $sender,
                "sms" => $message,
                "type" => "plain",
                "channel" => "generic",

            ];
            $response = Http::post($apiEndpoint, $payload);

            if (!$response->successful()) {
                logger("Termii Error", [$response->json()]);
                throw new \Exception($response->json()['message'] ?? __("An error occurred please try again later"), 1);
            }
        } else if ($enabledSmsGateway == "africastalking") {

            //
            $authKey = env("AFRICASTALKING_AUTHKEY");
            $sender = env("AFRICASTALKING_SENDER");
            $username = env("AFRICASTALKING_TOKEN");

            $url = "https://api.africastalking.com/version1/messaging";
            if (!\App::environment('production')) {
                $url = "https://api.sandbox.africastalking.com/version1/messaging";
            }

            $ch = curl_init();
            $postData = "username=" . $username . "&to=" . $phone . "&message=" . curl_escape($ch, $message) . "";
            if (!empty($sender)) {
                $postData .= "&from=" . $sender . "";
            }

            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/x-www-form-urlencoded",
                    "apiKey: " . $authKey . "",
                )
            ));


            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output = curl_exec($ch);
            $isError = 0;
            $errorMessage = true;
            logger("output", [$output]);
            //Print error if any
            if (curl_errno($ch)) {
                $isError = true;
                $errorMessage = curl_error($ch);
            }
            curl_close($ch);
            if ($isError) {
                throw new \Exception($errorMessage, 1);
            }
        } else if ($enabledSmsGateway == "hubtel") {
            $username = env("HUBTEL_AUTHKEY");
            $password = env("HUBTEL_TOKEN");
            $sender = env("HUBTEL_SENDER");

            $response = Http::get(
                "https://smsc.hubtel.com/v1/messages/send?clientsecret={" . $password . "}&clientid={" . $username . "}&from={" . $sender . "}&to=" . $phone . "&content=" . urlencode($message) . ""
            );

            if (!$response->successful()) {
                throw new \Exception($response->body(), 1);
            }
        }
        //custom code



        //send to email too if possible
        if ($user == null || $code == null) {
            return null;
        }
        //send code via email
        try {
            MailHandlerService::sendMail(new OTPMail($code, $user), $user->email);
            return __("OTP has been sent to your provided phone number and email address associated with the account");
        } catch (\Exception $ex) {
            logger("Mail Error: please check your mail server settings", [$ex]);
        }

        //return response
        return null;
    }









    //misc.

}