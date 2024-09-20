<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\VendorType;
use Illuminate\Http\Request;




class AppSettingsController extends Controller
{

    public function index(Request $request)
    {
        //
        $currency = Currency::where('country_code', setting("currencyCountryCode", "GH"))->first();

        //vendor types
        $vendorTypes = VendorType::active()->get();
        $showCart = VendorType::active()->sales()->count() > 0;
        //ui data
        $uiData = setting("ui");
        $uiData["home"]["showBannerOnHomeScreen"] = (bool) setting("ui.home.showBannerOnHomeScreen", 0);
        $uiData["home"]["showWalletOnHomeScreen"] = (bool) setting("ui.home.showWalletOnHomeScreen", true);

        return response()->json([
            "colors" => setting("appColorTheme"),
            "strings" => [
                "page" => setting('page'),
                "google_maps_key" =>  env('googleMapKey'),
                "fcm_key" =>  setting('fcmServerKey', ""),
                "app_name" => setting('appName', ""),
                "company_name" => setting('websiteName', ""),
                "enble_otp" => setting('enableOTP', "1"),
                "enableOTPLogin" => setting('enableOTPLogin', "0"),
                "enableEmailLogin" => (bool) setting('enableEmailLogin', 1),
                "enableProfileUpdate" => (bool) setting('enableProfileUpdate', 1),
                "otpGateway" => setting('otpGateway', ""),
                "enableGoogleDistance" => setting('enableGoogleDistance', "0"),
                "enableSingleVendor" => setting('enableSingleVendor', "0"),
                "enableMultipleVendorOrder" => (bool) setting('enableMultipleVendorOrder', 0),
                "enableProofOfDelivery" => setting('enableProofOfDelivery', "1"),
                "orderVerificationType" => setting('orderVerificationType'),
                "enableDriverWallet" => setting('enableDriverWallet', "0"),
                "enableGroceryMode" => setting('enableGroceryMode', "0"),
                "partnersCanRegister" => setting('partnersCanRegister', 0),
                "enableReferSystem" => setting('enableReferSystem', "0"),
                "referAmount" => setting('referRewardAmount', "0"),
                "enableChat" => setting('enableChat', "1"),
                "enableOrderTracking" => setting('enableOrderTracking', 1),
                "enableUploadPrescription" => setting('enableUploadPrescription', 1),
                "enableFatchByLocation" => (bool) setting('enableFatchByLocation'),
                //driver related
                "enableDriverTypeSwitch" => setting('enableDriverTypeSwitch', 0),
                "alertDuration" => setting('alertDuration', 15),
                "driverSearchRadius" => setting('driverSearchRadius', 10),
                "maxDriverOrderAtOnce" => setting('maxDriverOrderAtOnce', 1),
                "distanceCoverLocationUpdate" => setting('distanceCoverLocationUpdate', 10),
                "timePassLocationUpdate" => setting('timePassLocationUpdate', 10),
                "bannerHeight" => (float) setting('bannerHeight', 150),
                "showVendorTypeImageOnly" => setting('showVendorTypeImageOnly', 0),
                "autoassignmentsystem" => (int) setting('autoassignmentsystem', 0),
                //
                "enableParcelVendorByLocation" => setting('enableParcelVendorByLocation', "0"),
                "referRewardAmount" => setting('referRewardAmount', "0"),
                "enableParcelMultipleStops" => setting('enableParcelMultipleStops', "0"),
                "maxParcelStops" => setting('maxParcelStops', "1"),
                "what3wordsApiKey" => env('what3wordsApiKey'),
                "currency" => $currency->symbol,
                "country_code" => setting('countryCode', "GH"),
                //links
                "androidDownloadLink" => setting('androidDownloadLink', ""),
                "iosDownloadLink" => setting('iosDownloadLink', ""),
                //
                "isSingleVendorMode" => setting('enableSingleVendor', "0"),
                "enabledVendorType" => $vendorTypes->first(),
                //
                "emergencyContact" => setting('emergencyContact', "911"),

                //auth
                "auth" => [
                    "googleLogin" => (bool) setting("googleLogin"),
                    "appleLogin" => (bool) setting("appleLogin"),
                    "facebbokLogin" => (bool) setting("facebbokLogin"),
                    "qrcodeLogin" => (bool) setting("qrcodeLogin"),
                ],
                //ui
                "ui" => $uiData,
                //taxi
                "taxi" => setting("taxi"),
                //
                "map" => [
                    "useGoogleOnApp" => (int) env('useGoogleOnApp') ?? 1
                ],
                //finance
                "finance" => setting("finance"),
                //dynamic links
                "dynamic_link" => [
                    "prefix" => env('dynamic_link.prefix'),
                    "android" => env('dynamic_link.android'),
                    "ios" => env('dynamic_link.ios'),
                ],
                //for website
                "website" => [
                    "websiteHeaderTitle" => setting("websiteHeaderTitle"),
                    "websiteHeaderSubtitle" => setting("websiteHeaderSubtitle"),
                    "websiteHeaderImage" => url(setting("websiteHeaderImage")),
                    "websiteFooterImage" => url(setting("websiteFooterImage")),
                    "websiteIntroImage" => url(setting("websiteIntroImage", "")),
                    "websiteFooterBrief" => setting("websiteFooterBrief"),
                    "social" => setting("social"),
                ],
                //upgrade data
                "upgrade" => setting("upgrade"),
                //show cart
                "show_cart" => (bool) $showCart,
                //prescription file limit
                "file_limit" => [
                    "prescription" => setting("filelimit.prescription",),
                ],
            ],

        ]);
    }
}
