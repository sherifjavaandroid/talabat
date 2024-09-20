<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 2,
                'key' => 'currencyCode',
                'value' => 'USD',
            ),
            1 => 
            array (
                'id' => 3,
                'key' => 'currency',
                'value' => '$',
            ),
            2 => 
            array (
                'id' => 4,
                'key' => 'currencyCountryCode',
                'value' => 'US',
            ),
            3 => 
            array (
                'id' => 11,
                'key' => 'appColorTheme.accentColor',
                'value' => '#9755c1',
            ),
            4 => 
            array (
                'id' => 12,
                'key' => 'appColorTheme.primaryColor',
                'value' => '#4f1bb2',
            ),
            5 => 
            array (
                'id' => 13,
                'key' => 'appColorTheme.primaryColorDark',
                'value' => '#2e0c78',
            ),
            6 => 
            array (
                'id' => 14,
                'key' => 'appColorTheme.onboarding1Color',
                'value' => '#F9F9F9',
            ),
            7 => 
            array (
                'id' => 15,
                'key' => 'appColorTheme.onboarding2Color',
                'value' => '#F6EFEE',
            ),
            8 => 
            array (
                'id' => 16,
                'key' => 'appColorTheme.onboarding3Color',
                'value' => '#FFFBFC',
            ),
            9 => 
            array (
                'id' => 17,
                'key' => 'appColorTheme.onboardingIndicatorDotColor',
                'value' => '#a992ff',
            ),
            10 => 
            array (
                'id' => 18,
                'key' => 'appColorTheme.onboardingIndicatorActiveDotColor',
                'value' => '#4412ad',
            ),
            11 => 
            array (
                'id' => 19,
                'key' => 'appColorTheme.openColor',
                'value' => '#5ec3ff',
            ),
            12 => 
            array (
                'id' => 20,
                'key' => 'appColorTheme.closeColor',
                'value' => '#FF0000',
            ),
            13 => 
            array (
                'id' => 21,
                'key' => 'appColorTheme.deliveryColor',
                'value' => '#FFBF00',
            ),
            14 => 
            array (
                'id' => 22,
                'key' => 'appColorTheme.pickupColor',
                'value' => '#0000FF',
            ),
            15 => 
            array (
                'id' => 23,
                'key' => 'appColorTheme.ratingColor',
                'value' => '#FFBF00',
            ),
            16 => 
            array (
                'id' => 24,
                'key' => 'appColorTheme.pendingColor',
                'value' => '#0099FF',
            ),
            17 => 
            array (
                'id' => 25,
                'key' => 'appColorTheme.preparingColor',
                'value' => '#0000FF',
            ),
            18 => 
            array (
                'id' => 26,
                'key' => 'appColorTheme.enrouteColor',
                'value' => '#00FF00',
            ),
            19 => 
            array (
                'id' => 27,
                'key' => 'appColorTheme.failedColor',
                'value' => '#FF0000',
            ),
            20 => 
            array (
                'id' => 28,
                'key' => 'appColorTheme.cancelledColor',
                'value' => '#00ffff',
            ),
            21 => 
            array (
                'id' => 29,
                'key' => 'appColorTheme.deliveredColor',
                'value' => '#01A368',
            ),
            22 => 
            array (
                'id' => 30,
                'key' => 'appColorTheme.successfulColor',
                'value' => '#01A368',
            ),
            23 => 
            array (
                'id' => 32,
                'key' => 'appName',
                'value' => 'Glover',
            ),
            24 => 
            array (
                'id' => 33,
                'key' => 'websiteName',
                'value' => 'Glover',
            ),
            25 => 
            array (
                'id' => 34,
                'key' => 'countryCode',
                'value' => 'INTERNATIONAL,GH',
            ),
            26 => 
            array (
                'id' => 39,
                'key' => 'appVerisonCode',
                'value' => '74',
            ),
            27 => 
            array (
                'id' => 40,
                'key' => 'appVerison',
                'value' => '1.7.50',
            ),
            28 => 
            array (
                'id' => 41,
                'key' => 'otpGateway',
                'value' => 'Firebase',
            ),
            29 => 
            array (
                'id' => 42,
                'key' => 'appCountryCode',
                'value' => 'INTERNATIONAL,GH',
            ),
            30 => 
            array (
                'id' => 43,
                'key' => 'enableGoogleDistance',
                'value' => '0',
            ),
            31 => 
            array (
                'id' => 44,
                'key' => 'enableSingleVendor',
                'value' => '0',
            ),
            32 => 
            array (
                'id' => 45,
                'key' => 'enableProofOfDelivery',
                'value' => '1',
            ),
            33 => 
            array (
                'id' => 46,
                'key' => 'enableDriverWallet',
                'value' => '0',
            ),
            34 => 
            array (
                'id' => 47,
                'key' => 'driverWalletRequired',
                'value' => '0',
            ),
            35 => 
            array (
                'id' => 48,
                'key' => 'vendorEarningEnabled',
                'value' => '1',
            ),
            36 => 
            array (
                'id' => 49,
                'key' => 'alertDuration',
                'value' => '20',
            ),
            37 => 
            array (
                'id' => 50,
                'key' => 'driverSearchRadius',
                'value' => '10',
            ),
            38 => 
            array (
                'id' => 51,
                'key' => 'maxDriverOrderAtOnce',
                'value' => '3',
            ),
            39 => 
            array (
                'id' => 52,
                'key' => 'maxDriverOrderNotificationAtOnce',
                'value' => '5',
            ),
            40 => 
            array (
                'id' => 53,
                'key' => 'clearRejectedAutoAssignment',
                'value' => '5',
            ),
            41 => 
            array (
                'id' => 54,
                'key' => 'enableGroceryMode',
                'value' => '0',
            ),
            42 => 
            array (
                'id' => 55,
                'key' => 'enableReferSystem',
                'value' => '1',
            ),
            43 => 
            array (
                'id' => 56,
                'key' => 'enableChat',
                'value' => '1',
            ),
            44 => 
            array (
                'id' => 57,
                'key' => 'enableOrderTracking',
                'value' => '1',
            ),
            45 => 
            array (
                'id' => 58,
                'key' => 'enableParcelVendorByLocation',
                'value' => '0',
            ),
            46 => 
            array (
                'id' => 59,
                'key' => 'webviewDirection',
                'value' => 'ltr',
            ),
            47 => 
            array (
                'id' => 60,
                'key' => 'referRewardAmount',
                'value' => '10',
            ),
            48 => 
            array (
                'id' => 61,
                'key' => 'enableParcelMultipleStops',
                'value' => '1',
            ),
            49 => 
            array (
                'id' => 62,
                'key' => 'maxParcelStops',
                'value' => '4',
            ),
            50 => 
            array (
                'id' => 64,
                'key' => 'websiteHeaderTitle',
                'value' => 'The Future of Ordering Foods, Parcel, Grocery, Taxi and More',
            ),
            51 => 
            array (
                'id' => 65,
                'key' => 'websiteHeaderSubtitle',
                'value' => 'Glover is a multi-service delivery app that allows you to order food, parcel, grocery, taxi and more from your favourite local stores and restaurants. ',
            ),
            52 => 
            array (
                'id' => 67,
                'key' => 'social.fbLink',
                'value' => '',
            ),
            53 => 
            array (
                'id' => 68,
                'key' => 'social.igLink',
                'value' => '',
            ),
            54 => 
            array (
                'id' => 69,
                'key' => 'social.twLink',
                'value' => '',
            ),
            55 => 
            array (
                'id' => 70,
                'key' => 'websiteColor',
                'value' => '#4513ae',
            ),
            56 => 
            array (
                'id' => 71,
                'key' => 'locale',
                'value' => 'English',
            ),
            57 => 
            array (
                'id' => 72,
                'key' => 'localeCode',
                'value' => 'en',
            ),
            58 => 
            array (
                'id' => 73,
                'key' => 'timeZone',
                'value' => 'Africa/Accra',
            ),
            59 => 
            array (
                'id' => 74,
                'key' => 'maxScheduledDay',
                'value' => '5',
            ),
            60 => 
            array (
                'id' => 75,
                'key' => 'maxScheduledTime',
                'value' => '2',
            ),
            61 => 
            array (
                'id' => 76,
                'key' => 'minScheduledTime',
                'value' => '2',
            ),
            62 => 
            array (
                'id' => 77,
                'key' => 'autoCancelPendingOrderTime',
                'value' => '10',
            ),
            63 => 
            array (
                'id' => 78,
                'key' => 'defaultVendorRating',
                'value' => '5',
            ),
            64 => 
            array (
                'id' => 84,
                'key' => 'notifyAdmin',
                'value' => '1',
            ),
            65 => 
            array (
                'id' => 85,
                'key' => 'notifyCityAdmin',
                'value' => '1',
            ),
            66 => 
            array (
                'id' => 94,
                'key' => 'androidDownloadLink',
                'value' => '',
            ),
            67 => 
            array (
                'id' => 95,
                'key' => 'iosDownloadLink',
                'value' => '',
            ),
            68 => 
            array (
                'id' => 96,
                'key' => 'emergencyContact',
                'value' => '911',
            ),
            69 => 
            array (
                'id' => 97,
                'key' => 'driversCommission',
                'value' => '12',
            ),
            70 => 
            array (
                'id' => 98,
                'key' => 'vendorsCommission',
                'value' => '20',
            ),
            71 => 
            array (
                'id' => 99,
                'key' => 'taxi.cancelPendingTaxiOrderTime',
                'value' => '3',
            ),
            72 => 
            array (
                'id' => 100,
                'key' => 'taxi.msg.pending',
                'value' => 'Searching for driver',
            ),
            73 => 
            array (
                'id' => 101,
                'key' => 'taxi.msg.preparing',
                'value' => 'Driver assigned to your trip',
            ),
            74 => 
            array (
                'id' => 102,
                'key' => 'taxi.msg.ready',
                'value' => 'Driver arrived at your pickup location',
            ),
            75 => 
            array (
                'id' => 103,
                'key' => 'taxi.msg.enroute',
                'value' => 'Trip started',
            ),
            76 => 
            array (
                'id' => 104,
                'key' => 'taxi.msg.completed',
                'value' => 'Trip completed',
            ),
            77 => 
            array (
                'id' => 105,
                'key' => 'taxi.msg.cancelled',
                'value' => 'Trip cancelled',
            ),
            78 => 
            array (
                'id' => 106,
                'key' => 'taxi.msg.failed',
                'value' => 'Trip Failed',
            ),
            79 => 
            array (
                'id' => 107,
                'key' => 'clearFirestore',
                'value' => '1',
            ),
            80 => 
            array (
                'id' => 108,
                'key' => 'taxi.drivingSpeed',
                'value' => '30',
            ),
            81 => 
            array (
                'id' => 109,
                'key' => 'enableOTPLogin',
                'value' => '0',
            ),
            82 => 
            array (
                'id' => 110,
                'key' => 'enableUploadPrescription',
                'value' => '1',
            ),
            83 => 
            array (
                'id' => 116,
                'key' => 'minimumTopupAmount',
                'value' => '100',
            ),
            84 => 
            array (
                'id' => 117,
                'key' => 'googleLogin',
                'value' => '0',
            ),
            85 => 
            array (
                'id' => 118,
                'key' => 'appleLogin',
                'value' => '0',
            ),
            86 => 
            array (
                'id' => 119,
                'key' => 'facebbokLogin',
                'value' => '0',
            ),
            87 => 
            array (
                'id' => 120,
                'key' => 'distanceCoverLocationUpdate',
                'value' => '2',
            ),
            88 => 
            array (
                'id' => 121,
                'key' => 'timePassLocationUpdate',
                'value' => '30',
            ),
            89 => 
            array (
                'id' => 122,
                'key' => 'taxi.multipleCurrency',
                'value' => '0',
            ),
            90 => 
            array (
                'id' => 123,
                'key' => 'orderVerificationType',
                'value' => 'signature',
            ),
            91 => 
            array (
                'id' => 124,
                'key' => 'vendorsHomePageListCount',
                'value' => '15',
            ),
            92 => 
            array (
                'id' => 125,
                'key' => 'bannerHeight',
                'value' => '160',
            ),
            93 => 
            array (
                'id' => 126,
                'key' => 'allowVendorCreateDrivers',
                'value' => '1',
            ),
            94 => 
            array (
                'id' => 127,
                'key' => 'showVendorTypeImageOnly',
                'value' => '1',
            ),
            95 => 
            array (
                'id' => 131,
                'key' => 'vendorSetDeliveryFee',
                'value' => '0',
            ),
            96 => 
            array (
                'id' => 135,
                'key' => 'pos.printReciept',
                'value' => '1',
            ),
            97 => 
            array (
                'id' => 136,
                'key' => 'pos.showLogo',
                'value' => '0',
            ),
            98 => 
            array (
                'id' => 137,
                'key' => 'pos.paperSize',
                'value' => '300',
            ),
            99 => 
            array (
                'id' => 138,
                'key' => 'pos.showVendorDetails',
                'value' => '0',
            ),
            100 => 
            array (
                'id' => 139,
                'key' => 'pos.outro',
                'value' => 'thank you for shoping',
            ),
            101 => 
            array (
                'id' => 140,
                'key' => 'cronJobLastRun',
                'value' => '02 Aug 2024 at 11:40:02 am',
            ),
            102 => 
            array (
                'id' => 146,
                'key' => 'cronJobLastRunRaw',
                'value' => '2024-08-02 11:40:02',
            ),
            103 => 
            array (
                'id' => 147,
                'key' => 'ui.home.showBannerOnHomeScreen',
                'value' => '1',
            ),
            104 => 
            array (
                'id' => 148,
                'key' => 'partnersCanRegister',
                'value' => '1',
            ),
            105 => 
            array (
                'id' => 149,
                'key' => 'taxi.canScheduleTaxiOrder',
                'value' => '1',
            ),
            106 => 
            array (
                'id' => 150,
                'key' => 'qrcodeLogin',
                'value' => '1',
            ),
            107 => 
            array (
                'id' => 151,
                'key' => 'autoassignment_status',
                'value' => 'ready',
            ),
            108 => 
            array (
                'id' => 152,
                'key' => 'ui.home.vendortypePerRow',
                'value' => '3',
            ),
            109 => 
            array (
                'id' => 153,
                'key' => 'ui.home.bannerPosition',
                'value' => 'Top',
            ),
            110 => 
            array (
                'id' => 154,
                'key' => 'ui.home.vendortypeListStyle',
                'value' => 'Both',
            ),
            111 => 
            array (
                'id' => 155,
                'key' => 'useFCMJob',
                'value' => '1',
            ),
            112 => 
            array (
                'id' => 156,
                'key' => 'delayFCMJobSeconds',
                'value' => '1',
            ),
            113 => 
            array (
                'id' => 157,
                'key' => 'taxi.taxiMaxScheduleDays',
                'value' => '3',
            ),
            114 => 
            array (
                'id' => 239,
                'key' => 'taxiUseFirebaseServer',
                'value' => '0',
            ),
            115 => 
            array (
                'id' => 240,
                'key' => 'taxiDelayTaxiMatching',
                'value' => '2',
            ),
            116 => 
            array (
                'id' => 241,
                'key' => 'delayResearchTaxiMatching',
                'value' => '30',
            ),
            117 => 
            array (
                'id' => 242,
                'key' => 'enableFatchByLocation',
                'value' => '1',
            ),
            118 => 
            array (
                'id' => 243,
                'key' => 'enableNumericOrderCode',
                'value' => '1',
            ),
            119 => 
            array (
                'id' => 249,
                'key' => 'enableMultipleVendorOrder',
                'value' => '1',
            ),
            120 => 
            array (
                'id' => 250,
                'key' => 'walletTopupPercentage',
                'value' => '80',
            ),
            121 => 
            array (
                'id' => 251,
                'key' => 'finance.allowWalletTransfer',
                'value' => '1',
            ),
            122 => 
            array (
                'id' => 252,
                'key' => 'finance.fullInfoRequired',
                'value' => '0',
            ),
            123 => 
            array (
                'id' => 255,
                'key' => 'finance.preventOrderCancellation',
                'value' => '["ready","enroute","delivered"]',
            ),
            124 => 
            array (
                'id' => 256,
                'key' => 'finance.autoRefund',
                'value' => '0',
            ),
            125 => 
            array (
                'id' => 257,
                'key' => 'inapp.support',
                'value' => '
<script type="text/javascript">
window.location = "https://tawk.to/chat/5d2378ca7a48df6da2438c6a/default"
</script>
<!--End of Tawk.to Script-->',
            ),
            126 => 
            array (
                'id' => 258,
                'key' => 'upgrade.customer.android',
                'value' => '36',
            ),
            127 => 
            array (
                'id' => 259,
                'key' => 'upgrade.customer.ios',
                'value' => '36',
            ),
            128 => 
            array (
                'id' => 260,
                'key' => 'upgrade.customer.force',
                'value' => '0',
            ),
            129 => 
            array (
                'id' => 261,
                'key' => 'upgrade.driver.android',
                'value' => '33',
            ),
            130 => 
            array (
                'id' => 262,
                'key' => 'upgrade.driver.ios',
                'value' => '33',
            ),
            131 => 
            array (
                'id' => 263,
                'key' => 'upgrade.driver.force',
                'value' => '0',
            ),
            132 => 
            array (
                'id' => 264,
                'key' => 'upgrade.vendor.android',
                'value' => '33',
            ),
            133 => 
            array (
                'id' => 265,
                'key' => 'upgrade.vendor.ios',
                'value' => '33',
            ),
            134 => 
            array (
                'id' => 266,
                'key' => 'upgrade.vendor.force',
                'value' => '0',
            ),
            135 => 
            array (
                'id' => 269,
                'key' => 'map.geocoderType',
                'value' => 'Google',
            ),
            136 => 
            array (
                'id' => 270,
                'key' => 'map.useGoogleOnApp',
                'value' => '',
            ),
            137 => 
            array (
                'id' => 293,
                'key' => 'auto_create_social_account',
                'value' => '1',
            ),
            138 => 
            array (
                'id' => 294,
                'key' => 'enableDriverTypeSwitch',
                'value' => '0',
            ),
            139 => 
            array (
                'id' => 490,
                'key' => 'finance.allowWallet',
                'value' => '1',
            ),
            140 => 
            array (
                'id' => 491,
                'key' => 'finance.generalTax',
                'value' => '0',
            ),
            141 => 
            array (
                'id' => 492,
                'key' => 'finance.minOrderAmount',
                'value' => '0',
            ),
            142 => 
            array (
                'id' => 493,
                'key' => 'finance.maxOrderAmount',
                'value' => '1000000',
            ),
            143 => 
            array (
                'id' => 494,
                'key' => 'finance.amount_to_point',
                'value' => '0.001',
            ),
            144 => 
            array (
                'id' => 495,
                'key' => 'finance.point_to_amount',
                'value' => '0.001',
            ),
            145 => 
            array (
                'id' => 496,
                'key' => 'finance.enableLoyalty',
                'value' => '1',
            ),
            146 => 
            array (
                'id' => 497,
                'key' => 'finance.delivery.charge_per_km',
                'value' => '0',
            ),
            147 => 
            array (
                'id' => 498,
                'key' => 'finance.delivery.base_delivery_fee',
                'value' => '5',
            ),
            148 => 
            array (
                'id' => 499,
                'key' => 'finance.delivery.delivery_fee',
                'value' => '10',
            ),
            149 => 
            array (
                'id' => 500,
                'key' => 'finance.delivery.delivery_range',
                'value' => '',
            ),
            150 => 
            array (
                'id' => 501,
                'key' => 'finance.delivery.collectDeliveryCash',
                'value' => '1',
            ),
            151 => 
            array (
                'id' => 503,
                'key' => 'map.placeFilterCountryCodes',
                'value' => '',
            ),
            152 => 
            array (
                'id' => 504,
                'key' => 'driverWalletRequiredForTotal',
                'value' => '0',
            ),
            153 => 
            array (
                'id' => 521,
                'key' => 'autoassignmentsystem',
                'value' => '0',
            ),
            154 => 
            array (
                'id' => 522,
                'key' => 'taxi.msg.cash_overdraft_completed',
                'value' => '',
            ),
            155 => 
            array (
                'id' => 523,
                'key' => 'taxi.msg.overdraft_completed',
                'value' => '',
            ),
            156 => 
            array (
                'id' => 524,
                'key' => 'taxi.recalculateFare',
                'value' => '1',
            ),
            157 => 
            array (
                'id' => 553,
                'key' => 'ui.categorySize.w',
                'value' => '60',
            ),
            158 => 
            array (
                'id' => 554,
                'key' => 'ui.categorySize.h',
                'value' => '60',
            ),
            159 => 
            array (
                'id' => 555,
                'key' => 'ui.categorySize.text.size',
                'value' => '12',
            ),
            160 => 
            array (
                'id' => 556,
                'key' => 'ui.categorySize.row',
                'value' => '4',
            ),
            161 => 
            array (
                'id' => 557,
                'key' => 'ui.categorySize.page',
                'value' => '8',
            ),
            162 => 
            array (
                'id' => 558,
                'key' => 'ui.currency.location',
                'value' => 'Left',
            ),
            163 => 
            array (
                'id' => 559,
                'key' => 'ui.currency.format',
                'value' => ',',
            ),
            164 => 
            array (
                'id' => 560,
                'key' => 'ui.currency.decimal_format',
                'value' => '.',
            ),
            165 => 
            array (
                'id' => 561,
                'key' => 'ui.currency.decimals',
                'value' => '2',
            ),
            166 => 
            array (
                'id' => 562,
                'key' => 'ui.chat.canVendorChat',
                'value' => '1',
            ),
            167 => 
            array (
                'id' => 563,
                'key' => 'ui.chat.canCustomerChat',
                'value' => '1',
            ),
            168 => 
            array (
                'id' => 564,
                'key' => 'ui.chat.canDriverChat',
                'value' => '1',
            ),
            169 => 
            array (
                'id' => 565,
                'key' => 'ui.showVendorPhone',
                'value' => '0',
            ),
            170 => 
            array (
                'id' => 585,
                'key' => 'ui.home.showWalletOnHomeScreen',
                'value' => '1',
            ),
            171 => 
            array (
                'id' => 586,
                'key' => 'ui.home.homeViewStyle',
                'value' => '1',
            ),
            172 => 
            array (
                'id' => 587,
                'key' => 'ui.home.vendortypeHeight',
                'value' => '100',
            ),
            173 => 
            array (
                'id' => 588,
                'key' => 'ui.home.vendortypeWidth',
                'value' => '',
            ),
            174 => 
            array (
                'id' => 589,
                'key' => 'ui.home.vendortypeImageStyle',
                'value' => 'fill',
            ),
            175 => 
            array (
                'id' => 590,
                'key' => 'enableEmailLogin',
                'value' => '1',
            ),
            176 => 
            array (
                'id' => 694,
                'key' => 'finance.driverSelfPay',
                'value' => '0',
            ),
            177 => 
            array (
                'id' => 695,
                'key' => 'finance.orderOnlinePaymentTimeout',
                'value' => '10',
            ),
            178 => 
            array (
                'id' => 696,
                'key' => 'finance.walletTopupPaymentTimeout',
                'value' => '10',
            ),
            179 => 
            array (
                'id' => 697,
                'key' => 'finance.vendorSubscriptionPaymentTimeout',
                'value' => '10',
            ),
            180 => 
            array (
                'id' => 703,
                'key' => 'resourceLocation',
                'value' => 'us-central1',
            ),
            181 => 
            array (
                'id' => 704,
                'key' => 'productDetailsUpdateRequest',
                'value' => '1',
            ),
            182 => 
            array (
                'id' => 705,
                'key' => 'enableOnRegistrationReferReward',
                'value' => '1',
            ),
            183 => 
            array (
                'id' => 706,
                'key' => 'enableProfileUpdate',
                'value' => '0',
            ),
            184 => 
            array (
                'id' => 707,
                'key' => 'ui.categoryStyle',
                'value' => 'List',
            ),
            185 => 
            array (
                'id' => 709,
                'key' => 'websiteFeatures',
                'value' => '[{"title":"Ordering","description":"Enjoy your favorite meals from local restaurants delivered straight to your door. Choose from a wide variety of cuisines and restaurants to satisfy any craving. ","image":null,"image_url":"\\/storage\\/website\\/features\\/tsgbjcbAGNc0-1722378237.png"},{"title":"Fast Delivery","description":"Get items delivered faster to your doorstep with ease","image":null,"image_url":"\\/storage\\/website\\/features\\/q269LmH4sQWT-1722378237.png"},{"title":"Cheaper & Better","description":"Ordering item should not be expensive, with less money you can still get quality item from glover","image":null,"image_url":"\\/storage\\/website\\/features\\/nrP9cb18tbVO-1722378237.png"}]',
            ),
            186 => 
            array (
                'id' => 710,
                'key' => 'websiteFeatureTitle',
                'value' => 'Glover your go-to super app ',
            ),
            187 => 
            array (
                'id' => 711,
                'key' => 'websiteFeatureSubtitle',
                'value' => 'Discover the powerful features that make Glover your go-to super app for all your daily needs from food - parcel - grocery - taxi and more. ',
            ),
            188 => 
            array (
                'id' => 712,
                'key' => 'websiteDriverJoinTitle',
                'value' => 'Drive for us',
            ),
            189 => 
            array (
                'id' => 713,
                'key' => 'websiteDriverJoinDescription',
                'value' => '<p>Join the Glover team and become a part of our growing community of drivers! As a Glover driver, you\'ll enjoy the flexibility, competitive earnings, and the satisfaction of providing a valuable service to your community.</p><h3><strong>Benefits</strong></h3><ul><li>Full control of your vehicle</li><li>Choose your deliveries</li><li>Choose your own hours</li><li>Accurate navigation</li><li>Easy &amp; fast payment</li></ul><h3><strong>How it works</strong></h3><ul><li>Receive delivery requests directly on the app</li><li>Pick up and deliver orders to customers</li><li>View earnings and track your progress</li><li>Fast payout options</li></ul><p>Ready to join us? <a href="#">Sign up now</a> and start your journey with Glover today!</p>',
            ),
            190 => 
            array (
                'id' => 714,
                'key' => 'websiteDriverJoinImage',
                'value' => '',
            ),
            191 => 
            array (
                'id' => 715,
                'key' => 'websiteVendorJoinTitle',
                'value' => 'Sell with us',
            ),
            192 => 
            array (
                'id' => 716,
                'key' => 'websiteVendorJoinDescription',
                'value' => '<p>Join the Glover team and become a part of our growing community of drivers! As a Glover driver, you\'ll enjoy the flexibility, competitive earnings, and the satisfaction of providing a valuable service to your community.</p><h3><strong>Benefits</strong></h3><ul><li>Full control of your vehicle</li><li>Choose your deliveries</li><li>Choose your own hours</li><li>Accurate navigation</li><li>Easy &amp; fast payment</li></ul><h3><strong>How it works</strong></h3><ul><li>Receive delivery requests directly on the app</li><li>Pick up and deliver orders to customers</li><li>View earnings and track your progress</li><li>Fast payout options</li></ul><p>Ready to join us? <a href="#">Sign up now</a> and start your journey with Glover today!</p>',
            ),
            193 => 
            array (
                'id' => 717,
                'key' => 'websiteVendorJoinImage',
                'value' => '',
            ),
            194 => 
            array (
                'id' => 718,
                'key' => 'websiteAboutUs',
                'value' => '<p>At Glover, we believe in making life simpler and more convenient. As a comprehensive super app, Glover integrates food delivery, parcel services, grocery shopping, and taxi hailing all in one place, ensuring you have access to essential services right at your fingertips.</p><p>Our mission is to connect communities through innovative technology, providing seamless access to everyday services. We strive to offer reliable, efficient, and high-quality solutions that cater to the diverse needs of our users.</p>',
            ),
            195 => 
            array (
                'id' => 719,
                'key' => 'websiteContactUs',
                'value' => '<p>We\'re always here to assist you with any questions or concerns you may have. Whether you need help with our services, have feedback, or just want to get in touch, feel free to reach out to us through the following channels:</p><p>&nbsp;</p><p>Have questions or feedback? We\'re here to help! Reach out to our customer support team at <a href="mailto:info@edentech.online.com"><u>info@edentech.online.com </u></a>or call us at <a href="tel:+234 123 456 7890"><u>+234 123 456 7890</u> </a>.</p><p>&nbsp;</p><p>Your satisfaction is our priority, and we\'re committed to providing you with the best possible service. Thank you for choosing Glover!</p>',
            ),
            196 => 
            array (
                'id' => 796,
                'key' => 'social.yuLink',
                'value' => '',
            ),
        ));
        
        
    }
}