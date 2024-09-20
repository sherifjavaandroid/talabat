<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\SmsGateway;
use Exception;
use LVR\Colour\Hex;
use Illuminate\Support\Facades\Schema;

class AppSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $appName;
    public $enableOTP;
    public $enableOTPLogin;
    public $enableEmailLogin;
    public $enableProfileUpdate;
    public $otpGateway;
    public $appCountryCode;
    public $customPhoneNumberRegex;
    public $enableGoogleDistance;
    public $enableSingleVendor;
    public $enableMultipleVendorOrder;
    public $enableChat;
    public $enableDriverTypeSwitch;
    public $enableOrderTracking;
    public $enableUploadPrescription;
    public $enableParcelMultipleStops;
    public $maxParcelStops;
    public $clearFirestore;
    public $enableNumericOrderCode;
    public $orderVerificationType;
    public $vendorsHomePageListCount;
    public $productsPerPage;
    public $partnersCanRegister;
    public $enableFatchByLocation;

    //login
    public $googleLogin;
    public $appleLogin;
    public $facebbokLogin;
    public $qrcodeLogin;
    public $auto_create_social_account;


    //colors
    public $accentColor;
    public $primaryColor;
    public $primaryColorDark;
    public $onboarding1Color;
    public $onboarding2Color;
    public $onboarding3Color;
    //
    public $onboardingIndicatorDotColor;
    public $onboardingIndicatorActiveDotColor;
    public $openColor;
    public $closeColor;
    public $deliveryColor;
    public $pickupColor;
    public $ratingColor;
    public $pendingColor;
    public $preparingColor;
    public $enrouteColor;
    public $failedColor;
    public $cancelledColor;
    public $deliveredColor;
    public $successfulColor;


    // driver releated
    public $enableProofOfDelivery;
    public $enableDriverWallet;
    public $alertDuration;
    public $driverWalletRequired;
    public $vendorEarningEnabled;
    public $driverSearchRadius;
    public $maxDriverOrderAtOnce;
    public $maxDriverOrderNotificationAtOnce;
    public $clearRejectedAutoAssignment;
    public $emergencyContact;
    public $distanceCoverLocationUpdate;
    public $timePassLocationUpdate;
    public $allowVendorCreateDrivers;
    public $showVendorTypeImageOnly;
    public $statuses = [];
    public $autoassignmentStatus;
    public $systemTypes = [
        [
            "id" => 0,
            "name" => "Matching via server",
        ], [
            "id" => 1,
            "name" => "On Driver Device",
        ]
    ];
    public $fetchDriversystemTypes = [
        [
            "id" => 0,
            "name" => "Server Fetch API",
        ],
        [
            "id" => 1,
            "name" => "Firebase Cloud Function",
        ]
    ];
    public $autoassignmentsystem;
    public $fetchNearbyDriverSystem;

    //
    public $smsGateways = [];
    public $androidDownloadLink;
    public $iosDownloadLink;

    //order related
    public $orderRetryAfter;
    public $allowOldUnEncryptedOrder;


    public function mount()
    {
        //sms gateways
        $this->smsGateways = ['None', 'Firebase'];
        //
        $mSmsGateways = SmsGateway::all()->pluck('slug')->toArray();
        foreach ($mSmsGateways as $smsGateway) {
            $this->smsGateways[] = $smsGateway;
        }



        $this->appName = setting('appName', env('APP_NAME'));
        $this->enableOTP = (bool) setting('enableOTP');
        $this->enableOTPLogin = (bool) setting('enableOTPLogin');
        $this->enableEmailLogin = (bool) setting('enableEmailLogin', true);
        $this->enableProfileUpdate = (bool) setting('enableProfileUpdate', true);
        $this->otpGateway = setting('otpGateway');
        $this->appCountryCode = setting('countryCode', 'GH');
        $this->customPhoneNumberRegex = env("CUSTOM_PHONE_NUMBER_REGEX", "");
        $this->enableGoogleDistance = (bool) setting('enableGoogleDistance', 1);
        $this->enableSingleVendor = (bool) setting('enableSingleVendor');
        $this->enableMultipleVendorOrder = (bool) setting('enableMultipleVendorOrder');
        $this->enableProofOfDelivery = (bool) setting('enableProofOfDelivery');
        $this->orderVerificationType = setting('orderVerificationType', 'none');
        $this->enableDriverWallet = (bool) setting('enableDriverWallet');
        $this->driverWalletRequired = (bool) setting('driverWalletRequired');
        $this->vendorEarningEnabled = (bool) setting('vendorEarningEnabled');
        $this->clearFirestore = (bool) setting('clearFirestore');
        $this->enableNumericOrderCode = (bool) setting('enableNumericOrderCode');
        $this->vendorsHomePageListCount = (int) setting('vendorsHomePageListCount', 15);
        $this->productsPerPage = (int) setting('productsPerPage', 30);
        $this->enableFatchByLocation = (bool) setting('enableFatchByLocation');


        //login
        $this->googleLogin = (bool) setting('googleLogin');
        $this->appleLogin = (bool) setting('appleLogin');
        $this->facebbokLogin = (bool) setting('facebbokLogin');
        $this->qrcodeLogin = (bool) setting('qrcodeLogin');
        $this->auto_create_social_account = (bool) setting('auto_create_social_account', 0);


        $this->alertDuration = (int) setting('alertDuration', 15);
        $this->enableChat = (bool) setting('enableChat');
        $this->enableOrderTracking = (bool) setting('enableOrderTracking', true);
        $this->enableUploadPrescription = (bool) setting('enableUploadPrescription', true);
        $this->enableParcelMultipleStops = (bool) setting('enableParcelMultipleStops');
        $this->maxParcelStops = (float) setting('maxParcelStops');

        //
        $this->enableDriverTypeSwitch = (bool) setting('enableDriverTypeSwitch', 0);
        $this->driverSearchRadius = (float) setting('driverSearchRadius', 10);
        $this->maxDriverOrderAtOnce = (int) setting('maxDriverOrderAtOnce', 1);
        $this->maxDriverOrderNotificationAtOnce = (int) setting('maxDriverOrderNotificationAtOnce', 1);
        $this->clearRejectedAutoAssignment = (int) setting('clearRejectedAutoAssignment', 0);
        $this->emergencyContact = setting('emergencyContact', "911");
        $this->distanceCoverLocationUpdate = setting('distanceCoverLocationUpdate', "10");
        $this->timePassLocationUpdate = setting('timePassLocationUpdate', "10");
        $this->allowVendorCreateDrivers = (bool) setting('allowVendorCreateDrivers');
        $this->showVendorTypeImageOnly = (bool) setting('showVendorTypeImageOnly');
        $this->partnersCanRegister = (bool) setting('partnersCanRegister', true);
        $this->autoassignmentStatus =  setting('autoassignment_status', "ready");
        $this->autoassignmentsystem =  setting('autoassignmentsystem', 0);
        $this->fetchNearbyDriverSystem =  setting('fetchNearbyDriverSystem', 0);

        //
        $this->accentColor = setting('appColorTheme.accentColor', '#64bda1');
        $this->primaryColor = setting('appColorTheme.primaryColor', '#21a179');
        $this->primaryColorDark = setting('appColorTheme.primaryColorDark', '#146149');
        //
        $this->onboarding1Color = setting('appColorTheme.onboarding1Color', '#F9F9F9');
        $this->onboarding2Color = setting('appColorTheme.onboarding2Color', '#F6EFEE');
        $this->onboarding3Color = setting('appColorTheme.onboarding3Color', '#FFFBFC');
        //
        $this->onboardingIndicatorDotColor = setting('appColorTheme.onboardingIndicatorDotColor', '#30C0D9');
        $this->onboardingIndicatorActiveDotColor = setting('appColorTheme.onboardingIndicatorActiveDotColor', '#21a179');
        $this->openColor = setting('appColorTheme.openColor', '#00FF00');
        $this->closeColor = setting('appColorTheme.closeColor', '#FF0000');
        $this->deliveryColor = setting('appColorTheme.deliveryColor', '#FFBF00');
        $this->pickupColor = setting('appColorTheme.pickupColor', '#0000FF');
        $this->ratingColor = setting('appColorTheme.ratingColor', '#FFBF00');
        //
        $this->pendingColor = setting('appColorTheme.pendingColor', '#0099FF');
        $this->preparingColor = setting('appColorTheme.preparingColor', '#0000FF');
        $this->enrouteColor = setting('appColorTheme.enrouteColor', '#00FF00');
        $this->failedColor = setting('appColorTheme.failedColor', '#FF0000');
        $this->cancelledColor = setting('appColorTheme.cancelledColor', '#808080');
        $this->deliveredColor = setting('appColorTheme.deliveredColor', '#01A368');
        $this->successfulColor = setting('appColorTheme.successfulColor', '#01A368');
        //
        $this->androidDownloadLink = setting('androidDownloadLink');
        $this->iosDownloadLink = setting('iosDownloadLink');

        //
        $this->statuses = Order::getPossibleStatues();

        //order related
        $this->orderRetryAfter = (int) setting('orderRetryAfter', 20);
        $this->allowOldUnEncryptedOrder = (bool) setting('allowOldUnEncryptedOrder', 1);
    }

    public function render()
    {
        return view('livewire.settings.app-settings');
    }


    public function saveGeneralSettings()
    {

        $this->validate([
            "appName" => "required|string",
        ]);

        try {


            $this->isDemo();
            $appSettings = [
                'androidDownloadLink' =>  $this->androidDownloadLink,
                'iosDownloadLink' =>  $this->iosDownloadLink,
                'appName' =>  $this->appName,
                'clearFirestore' =>  $this->clearFirestore,
                'enableNumericOrderCode' =>  $this->enableNumericOrderCode,
                'enableParcelMultipleStops' =>  $this->enableParcelMultipleStops,
                'maxParcelStops' =>  $this->maxParcelStops,
                'enableProfileUpdate' =>  $this->enableProfileUpdate,
                'countryCode' =>  $this->appCountryCode,
            ];

            // update the site name
            setting($appSettings)->save();

            //save customPhoneNumberRegex
            setEnv("CUSTOM_PHONE_NUMBER_REGEX", $this->customPhoneNumberRegex);

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }

    public function saveAuthSettings()
    {


        try {

            $this->isDemo();

            //make the phone column nullable
            if ($this->auto_create_social_account) {

                $columnType = Schema::getColumnType('users', 'phone');
                $isNullable = (strpos($columnType, 'nullable') !== false);
                if (!$isNullable) {
                    Schema::table("users", function ($table) {
                        $table->string('phone')->nullable()->change();
                    });
                }
            }


            //make sure at least one login is enabled
            if (!$this->enableOTPLogin && !$this->enableEmailLogin) {
                throw new Exception(__("At least one login method must be enabled!"));
            }


            $appSettings = [
                'enableOTPLogin' =>  $this->enableOTPLogin,
                'enableEmailLogin' =>  $this->enableEmailLogin,
                'otpGateway' =>  $this->otpGateway,
                //logins
                'googleLogin' =>  $this->googleLogin,
                'appleLogin' =>  $this->appleLogin,
                'facebbokLogin' =>  $this->facebbokLogin,
                'qrcodeLogin' =>  $this->qrcodeLogin,
                'auto_create_social_account' =>  $this->auto_create_social_account,
            ];

            // update the site name
            setting($appSettings)->save();

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }

    public function saveLayoutSettings()
    {


        try {

            $this->isDemo();
            $appSettings = [
                'enableGoogleDistance' =>  $this->enableGoogleDistance,
                'enableSingleVendor' =>  $this->enableSingleVendor,
                'enableChat' =>  $this->enableChat,
                'enableOrderTracking' =>  $this->enableOrderTracking,
                'enableUploadPrescription' =>  $this->enableUploadPrescription,
                'enableProofOfDelivery' =>  $this->enableProofOfDelivery,
                'orderVerificationType' =>  $this->orderVerificationType,
                'vendorsHomePageListCount' =>  $this->vendorsHomePageListCount,
                "vendorsHomePageListCount" => $this->vendorsHomePageListCount,
                'allowVendorCreateDrivers' =>  $this->allowVendorCreateDrivers,
                'showVendorTypeImageOnly' =>  $this->showVendorTypeImageOnly,
                'partnersCanRegister' =>  $this->partnersCanRegister,
                'enableFatchByLocation' =>  $this->enableFatchByLocation,
                'enableMultipleVendorOrder' =>  $this->enableMultipleVendorOrder,
            ];

            // update the site name
            setting($appSettings)->save();

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }


    public function saveDriverSettings()
    {


        try {

            //driver search radius can't be more than 6,371km
            if ($this->driverSearchRadius > 6371) {
                $msg = __("Driver search radius can't be more than");
                $msg .= " 6,371km!";
                $this->addError('driverSearchRadius', $msg);
                return;
            }

            //minutes to seconds
            $resendSeconds = ($this->clearRejectedAutoAssignment * 60) ?? 0;
            //make sure resendSeconds is at least 2ice the alertDuration
            if ($resendSeconds < ($this->alertDuration * 2)) {
                $this->addError('clearRejectedAutoAssignment', __("Clear rejected auto assignment must be at least twice the alert duration!"));
                return;
            }


            $this->isDemo();
            $appSettings = [
                //default 15seconds
                'enableDriverTypeSwitch' =>  $this->enableDriverTypeSwitch,
                'alertDuration' =>  $this->alertDuration ?? 15,
                //default 10km radius
                'driverSearchRadius' =>  $this->driverSearchRadius ?? 10,
                //max driver order at once
                'maxDriverOrderAtOnce' =>  $this->maxDriverOrderAtOnce ?? 1,
                'maxDriverOrderNotificationAtOnce' =>  $this->maxDriverOrderNotificationAtOnce ?? 1,
                'clearRejectedAutoAssignment' =>  $this->clearRejectedAutoAssignment ?? 0,
                'emergencyContact' =>  $this->emergencyContact,
                'distanceCoverLocationUpdate' =>  $this->distanceCoverLocationUpdate,
                'timePassLocationUpdate' =>  $this->timePassLocationUpdate,
                'autoassignment_status' =>  $this->autoassignmentStatus,
                'autoassignmentsystem' =>  $this->autoassignmentsystem,
                'fetchNearbyDriverSystem' =>  $this->fetchNearbyDriverSystem,
            ];

            // update the site name
            setting($appSettings)->save();

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }

    public function saveThemeSettings()
    {

        $this->validate([
            'accentColor' => ['sometimes', 'nullable', new Hex],
            'primaryColor' => ['sometimes', 'nullable', new Hex],
            'primaryColorDark' => ['sometimes', 'nullable', new Hex],
        ]);

        try {

            $this->isDemo();
            $appSettings = [
                'appColorTheme' => [
                    "accentColor" => $this->accentColor,
                    "primaryColor" => $this->primaryColor,
                    "primaryColorDark" => $this->primaryColorDark,
                    //
                    "onboarding1Color" => $this->onboarding1Color,
                    "onboarding2Color" => $this->onboarding2Color,
                    "onboarding3Color" => $this->onboarding3Color,
                    //
                    "onboardingIndicatorDotColor" => $this->onboardingIndicatorDotColor,
                    "onboardingIndicatorActiveDotColor" => $this->onboardingIndicatorActiveDotColor,
                    "openColor" => $this->openColor,
                    "closeColor" => $this->closeColor,
                    "deliveryColor" => $this->deliveryColor,
                    "pickupColor" => $this->pickupColor,
                    "ratingColor" => $this->ratingColor,
                    "pendingColor" => $this->pendingColor,
                    "preparingColor" => $this->preparingColor,
                    "enrouteColor" => $this->enrouteColor,
                    "failedColor" => $this->failedColor,
                    "cancelledColor" => $this->cancelledColor,
                    "deliveredColor" => $this->deliveredColor,
                    "successfulColor" => $this->successfulColor,
                ]
            ];

            // update the site name
            setting($appSettings)->save();

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }

    public function saveOrderSettings()
    {

        $this->validate([
            'orderRetryAfter' => 'required|numeric|min:5',
        ]);

        try {

            $this->isDemo();
            // update order retry after
            setting([
                'orderRetryAfter' =>  $this->orderRetryAfter ?? 20,
                'allowOldUnEncryptedOrder' =>  $this->allowOldUnEncryptedOrder ?? 0,
            ])->save();

            $this->showSuccessAlert(__("Order settings saved successfully!"));
            $this->reset();
            $this->mount();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Order settings save failed!"));
        }
    }
}