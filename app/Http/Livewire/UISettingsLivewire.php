<?php

namespace App\Http\Livewire;

use Exception;

class UISettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $categorySize_w;
    public $categorySize_h;
    public $categorySize_text_size;
    public $categoryPerRow;
    public $categoryPerPage;
    public $categoryStyle;
    // Currency
    public $currencyLocation;
    public $currencyFormat;
    public $currencyDecimals;
    public $currencyDecimalFormat;
    //home
    public $showBannerOnHomeScreen;
    public $showWalletOnHomeScreen;
    public $vendortypePerRow;
    public $bannerHeight;
    public $bannerPosition;
    public $vendortypeListStyle;
    public $homeViewStyle;
    public $homeViewStyles = [
        [
            "id" => 1,
            "name" => "Original",
        ],
        [
            "id" => 2,
            "name" => "Modern",
        ],
        [
            "id" => 3,
            "name" => "Plain",
        ]
    ];

    public $vendortypeHeight;
    public $vendortypeWidth;
    public $vendortypeImageStyle;

    //misc
    public $showVendorPhone;
    public $showVendorAddress;
    public $canVendorChat;
    public $canVendorChatSupportMedia;
    public $canCustomerChat;
    public $canCustomerChatSupportMedia;
    public $canDriverChat;
    public $canDriverChatSupportMedia;
    //
    public $canCustomerVendorCall;
    public $canCustomerDriverCall;
    public $canDriverVendorCall;

    public function prepareData()
    {
        $this->categorySize_w = setting('ui.categorySize.w', 40);
        $this->categorySize_h = setting('ui.categorySize.h', 40);
        $this->categorySize_text_size = setting('ui.categorySize.text.size', 12);
        $this->categoryPerRow = setting('ui.categorySize.row', 4);
        $this->categoryPerPage = setting('ui.categorySize.page', 8);
        $this->categoryStyle = setting('ui.categoryStyle', 'grid');
        //
        $this->currencyLocation = setting('ui.currency.location', 8);
        $this->currencyFormat = setting('ui.currency.format', ",");
        $this->currencyDecimalFormat = setting('ui.currency.decimal_format', ".");
        $this->currencyDecimals = setting('ui.currency.decimals', 2);

        //
        $this->showBannerOnHomeScreen = (bool) setting('ui.home.showBannerOnHomeScreen', false);
        $this->showWalletOnHomeScreen = (bool) setting('ui.home.showWalletOnHomeScreen', true);
        $this->vendortypePerRow = setting('ui.home.vendortypePerRow', 2);
        $this->bannerPosition = setting('ui.home.bannerPosition', 'top');
        $this->bannerHeight = (float) setting('bannerHeight', 150);
        $this->vendortypeListStyle = setting('ui.home.vendortypeListStyle', 'both');
        $this->homeViewStyle = setting('ui.home.homeViewStyle', '1');
        $this->vendortypeHeight = setting('ui.home.vendortypeHeight', 100);
        $this->vendortypeWidth = setting('ui.home.vendortypeWidth', 100);
        $this->vendortypeImageStyle = setting('ui.home.vendortypeImageStyle', 'cover');

        //
        $this->showVendorPhone = (bool) setting('ui.showVendorPhone', true);
        $this->showVendorAddress = (bool) setting('ui.showVendorAddress', true);
        $this->canVendorChat = (bool) setting('ui.chat.canVendorChat', true);
        $this->canVendorChatSupportMedia = (bool) setting('ui.chat.canVendorChatSupportMedia', false);
        $this->canCustomerChat = (bool) setting('ui.chat.canCustomerChat', true);
        $this->canCustomerChatSupportMedia = (bool) setting('ui.chat.canCustomerChatSupportMedia', false);
        $this->canDriverChat = (bool) setting('ui.chat.canDriverChat', true);
        $this->canDriverChatSupportMedia = (bool) setting('ui.chat.canDriverChatSupportMedia', false);
        //calls
        $this->canCustomerVendorCall = (bool) setting('ui.call.canCustomerVendorCall', true);
        $this->canCustomerDriverCall = (bool) setting('ui.call.canCustomerDriverCall', true);
        $this->canDriverVendorCall = (bool) setting('ui.call.canDriverVendorCall', true);
    }

    public function render()
    {
        $this->prepareData();
        return view('livewire.settings.ui-settings');
    }


    public function saveHomeSettings()
    {

        try {

            $this->isDemo();
            $appSettings = [
                'ui.home' => [
                    "showWalletOnHomeScreen" => $this->showWalletOnHomeScreen,
                    "showBannerOnHomeScreen" => $this->showBannerOnHomeScreen,
                    "vendortypePerRow" => $this->vendortypePerRow,
                    "bannerPosition" => $this->bannerPosition,
                    "vendortypeListStyle" => $this->vendortypeListStyle,
                    "homeViewStyle" => $this->homeViewStyle,
                    "vendortypeHeight" => $this->vendortypeHeight,
                    "vendortypeWidth" => $this->vendortypeWidth,
                    "vendortypeImageStyle" => $this->vendortypeImageStyle,
                ],
            ];

            // update the site name
            setting($appSettings)->save();
            //update the banner height
            setting(['bannerHeight' => $this->bannerHeight])->save();



            $this->showSuccessAlert(__("Home Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Home Settings save failed!"));
        }
    }


    public function saveCategorySettings()
    {

        $this->validate([
            "categorySize_w" => "required|numeric",
            "categorySize_h" => "required|numeric",
            "categorySize_text_size" => "required|numeric",
            "categoryPerRow" => "required|numeric",
            "categoryPerPage" => "required|numeric",
        ]);

        try {

            $this->isDemo();
            $appSettings = [
                'ui.categorySize' => [
                    "w" => $this->categorySize_w,
                    "h" => $this->categorySize_h,
                    "row" => $this->categoryPerRow,
                    "page" => $this->categoryPerPage,
                    "text" => [
                        "size" => $this->categorySize_text_size,
                    ],
                ],
                'ui.categoryStyle' => $this->categoryStyle,
            ];

            // update the site name
            setting($appSettings)->save();



            $this->showSuccessAlert(__("Category Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Category Settings save failed!"));
        }
    }

    public function saveCurrencySettings()
    {

        $this->validate(
            [
                "currencyLocation" => "required",
                "currencyFormat" => "required",
                "currencyDecimals" => "required",
                "currencyDecimalFormat" => "required",
            ]
        );

        try {

            $this->isDemo();
            $appSettings = [
                'ui.currency' => [
                    "location" => $this->currencyLocation,
                    "format" => $this->currencyFormat,
                    "decimal_format" => $this->currencyDecimalFormat,
                    "decimals" => $this->currencyDecimals,
                ],
            ];

            // update the site name
            setting($appSettings)->save();



            $this->showSuccessAlert(__("Currency Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Currency Settings save failed!"));
        }
    }


    public function saveMiscSettings()
    {

        try {

            $this->isDemo();
            $appSettings = [
                'ui.chat' => [
                    "canVendorChat" => $this->canVendorChat,
                    "canVendorChatSupportMedia" => $this->canVendorChatSupportMedia,
                    "canCustomerChat" => $this->canCustomerChat,
                    "canCustomerChatSupportMedia" => $this->canCustomerChatSupportMedia,
                    "canDriverChat" => $this->canDriverChat,
                    "canDriverChatSupportMedia" => $this->canDriverChatSupportMedia,
                ],
                'ui.call' => [
                    "canCustomerVendorCall" => $this->canCustomerVendorCall,
                    "canCustomerDriverCall" => $this->canCustomerDriverCall,
                    "canDriverVendorCall" => $this->canDriverVendorCall,
                ],
                "ui.showVendorPhone" => $this->showVendorPhone,
                "ui.showVendorAddress" => $this->showVendorAddress,
            ];
            // update the site name
            setting($appSettings)->save();



            $this->showSuccessAlert(__("Misc. Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Misc. Settings save failed!"));
        }
    }
}