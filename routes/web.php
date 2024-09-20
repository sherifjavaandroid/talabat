<?php

use App\Http\Controllers\API\AuthRedirectController;
use App\Http\Livewire\Auth\LoginLivewire;
use App\Http\Livewire\Auth\RegisterLivewire;
use App\Http\Livewire\Auth\DriverRegisterLivewire;
use App\Http\Livewire\Auth\PasswordResetLivewire;
use App\Http\Livewire\Auth\ForgotPasswordLivewire;

use App\Http\Livewire\InAppSupportLivewire;
use App\Http\Livewire\InAppSupportPageLivewire;
use App\Http\Livewire\BannerLivewire;
use App\Http\Livewire\DashboardLivewire;
use App\Http\Livewire\TagLivewire;
use App\Http\Livewire\TagLinkLivewire;
use App\Http\Livewire\CategoryLivewire;
use App\Http\Livewire\SubCategoryLivewire;
use App\Http\Livewire\VendorTypeLivewire;
use App\Http\Livewire\VendorLivewire;
use App\Http\Livewire\VendorDetailsLivewire;
use App\Http\Livewire\ProductLivewire;
use App\Http\Livewire\ProductDetailsLivewire;
use App\Http\Livewire\ProductRequestLivewire;
use App\Http\Livewire\FavouriteLivewire;
use App\Http\Livewire\ReviewLivewire;
use App\Http\Livewire\ProductReviewLivewire;
use App\Http\Livewire\OptionGroupLivewire;
use App\Http\Livewire\MenuLivewire;
use App\Http\Livewire\OptionLivewire;
use App\Http\Livewire\WalletTransactionLivewire;
use App\Http\Livewire\PaymentAccountLivewire;

use App\Http\Livewire\ServiceLivewire;
use App\Http\Livewire\ServiceOptionGroupLivewire;
use App\Http\Livewire\ServiceOptionLivewire;

use App\Http\Livewire\OrderLivewire;
use App\Http\Livewire\NewOrderLivewire;
use App\Http\Livewire\OrderChatLivewire;
use App\Http\Livewire\RefundLivewire;
use App\Http\Livewire\CouponLivewire;
use App\Http\Livewire\DeliveryAddressLivewire;

use App\Http\Livewire\CurrencyLivewire;
use App\Http\Livewire\AppSettingsLivewire;
use App\Http\Livewire\MapSettingsLivewire;
use App\Http\Livewire\UISettingsLivewire;
use App\Http\Livewire\FinanceSettingsLivewire;
use App\Http\Livewire\WebsiteSettingsLivewire;
use App\Http\Livewire\ServerSettingsLivewire;
use App\Http\Livewire\SettingsLivewire;
use App\Http\Livewire\PageSettingsLivewire;
use App\Http\Livewire\CMSPageSettingsLivewire;
use App\Http\Livewire\DynamicLinkSettingsLivewire;
use App\Http\Livewire\WebhookSettingsLivewire;
use App\Http\Livewire\AppUpgradeSettingsLivewire;
use App\Http\Livewire\RoleManagerLivewire;
use App\Http\Livewire\PaymentMethodivewire;
use App\Http\Livewire\VendorPaymentMethodLivewire;
use App\Http\Livewire\Payment\OrderPaymentLivewire;
use App\Http\Livewire\Payment\OrderPaymentCallbackLivewire;
use App\Http\Livewire\Payment\PaymentProcessedLivewire;

use App\Http\Livewire\PackageTypeLivewire;
use App\Http\Livewire\NewPackageOrderLivewire;
use App\Http\Livewire\VendorNewPackageOrderLivewire;
use App\Http\Livewire\PackageTypePricingLivewire;
use App\Http\Livewire\CountryLivewire;
use App\Http\Livewire\StateLivewire;
use App\Http\Livewire\CitiesLivewire;
use App\Http\Livewire\VendorCitiesLivewire;
use App\Http\Livewire\VendorStatesLivewire;
use App\Http\Livewire\VendorCountriesLivewire;

use App\Http\Livewire\UserLivewire;
use App\Http\Livewire\UserPermissionLivewire;
use App\Http\Livewire\UserDetailsLivewire;
use App\Http\Livewire\DeletedUserLivewire;
use App\Http\Livewire\DriverLivewire;
use App\Http\Livewire\VendorDriverSettingLivewire;
use App\Http\Livewire\DriverEarningLivewire;
use App\Http\Livewire\DriverRemittanceLivewire;
use App\Http\Livewire\VendorEarningLivewire;
use App\Http\Livewire\MyEarningLivewire;
use App\Http\Livewire\PayoutLivewire;
use App\Http\Livewire\MyPayoutLivewire;
use App\Http\Livewire\WalletPaymentMethodLivewire;

use App\Http\Livewire\BackUpLivewire;
use App\Http\Livewire\DataLivewire;
use App\Http\Livewire\NotificationLivewire;
use App\Http\Livewire\TranslationLivewire;
use App\Http\Livewire\ImportLivewire;
use App\Http\Livewire\ExportLivewire;
use App\Http\Livewire\UpgradeLivewire;
use App\Http\Livewire\SMSGatewayLivewire;
use App\Http\Livewire\ExtensionLivewire;
use App\Http\Livewire\CronJobLivewire;
use App\Http\Livewire\AutoAssignmentLivewire;
use App\Http\Livewire\TroubleShootLivewire;

use App\Http\Livewire\Payment\WalletTopUpLivewire;
use App\Http\Livewire\Payment\WalletTopUpFailureLivewire;
use App\Http\Livewire\Payment\WalletTopUpCallbackLivewire;

use App\Http\Livewire\SubscriptionLivewire;
use App\Http\Livewire\SubscribeLivewire;
use App\Http\Livewire\MySubscriptionLivewire;
use App\Http\Livewire\VendorSubscriptionLivewire;
use App\Http\Livewire\Payment\SubscribeCallbackLivewire;

use App\Http\Livewire\ProfileLivewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//
use App\Http\Livewire\VehicleTypeLivewire;
use App\Http\Livewire\VehicleLivewire;
use App\Http\Livewire\CarMakeLivewire;
use App\Http\Livewire\CarModelLivewire;
use App\Http\Livewire\PaymentMethodVehicleTypeLivewire;
use App\Http\Livewire\TaxiSettingLivewire;
use App\Http\Livewire\TaxiPricingLivewire;
use App\Http\Livewire\DeliveryZoneLivewire;
use App\Http\Livewire\TaxiZoneLivewire;
use App\Http\Livewire\NewTaxiOrderLivewire;
//
use App\Http\Livewire\VendorDocumentRequestLivewire;
use App\Http\Livewire\DriverDocumentRequestLivewire;

//Reports
use App\Http\Livewire\Report\SummaryReportLivewire;
use App\Http\Livewire\Report\CouponReportLivewire;
use App\Http\Livewire\Report\ProductReportLivewire;
use App\Http\Livewire\Report\ServiceReportLivewire;
use App\Http\Livewire\Report\VendorReportLivewire;
use App\Http\Livewire\Report\SubscriptionReportLivewire;
use App\Http\Livewire\Report\CustomerReportLivewire;
use App\Http\Livewire\Report\ReferralReportLivewire;
use App\Http\Livewire\Report\CommissionReportLivewire;
use App\Http\Livewire\Report\LoyaltyReportLivewire;
use App\Http\Livewire\OutstandingPaymentLivewire;

// fleet manager
use App\Http\Livewire\FleetUsersLivewire;
use App\Http\Livewire\FleetVehiclesLivewire;
use App\Http\Livewire\FleetsLivewire;
use App\Http\Livewire\FleetOrderLivewire;
use App\Http\Livewire\SharePreviewLivewire;
use App\Http\Livewire\FlashSale\FlashSaleLivewire;

use App\Http\Livewire\Misc\OnboardingLivewire;
use App\Http\Livewire\Misc\FaqLivewire;
use App\Http\Livewire\VendorFeesLivewire;
use App\Http\Livewire\OrderPrintLivewire;

use App\Http\Livewire\DriverEarningHistoryLivewire;
use App\Http\Livewire\EditOrderLivewire;
use App\Http\Livewire\SystemDriverLivewire;
use App\Http\Livewire\VendorEarningHistoryLivewire;
use Illuminate\Http\Request;

use App\Http\Controllers\CMSPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {

    /*
    Route::get('preview/mail', function (Request $request) {
        return;
        //New vendor mail
        // $vendor = App\Models\Vendor::first();
        // return new App\Mail\NewVendorMail($vendor);
        // NEW USER MAIL
        // $user = App\Models\User::first();
        // return new App\Mail\NewAccountMail($user, "password");

        //vendor custom settings
        // $order = App\Models\Order::where('vendor_id', 174)->first();
        // return driverSearchRadius($order);

        $slug = $request->slug ?? "";
        $id = $request->id;
        if ($slug == 'taxi') {
            $order = App\Models\Order::whereHas('taxi_order')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else if ($slug == 'package') {
            $order = App\Models\Order::whereHas('package_type')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else if ($slug == 'service') {
            $order = App\Models\Order::whereHas('order_service')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else {
            //order update mail
            $order = App\Models\Order::whereHas('products')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        }
        // return $order;
        // $order = App\Models\Order::whereHas('taxi_order')->first();
        // $order = App\Models\Order::whereHas('package_type')->first();
        return new App\Mail\OrderUpdateMail($order);
    });
    */


    //empty
    Route::get('driver/document/instructions', function () {
        return  setting('page.settings.driverDocumentInstructions', __('Documents'));
    })->name('driver.document.instructions');
    Route::get('vendor/document/instructions', function () {
        return  setting('page.settings.vendorDocumentInstructions', __('Documents'));
    })->name('vendor.document.instructions');
    // Auth
    Route::get('login', LoginLivewire::class)->name('login');
    Route::get('register/driver', DriverRegisterLivewire::class)->name('register.driver');

    //hanlde people that still use register#vendor/register#driver
    Route::get('register', function (Request $request) {
        return view('livewire.auth.register.register-reroute');
    });




    Route::get('register/vendor', RegisterLivewire::class)->name('register.vendor');
    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    Route::get('password/forgot', ForgotPasswordLivewire::class)->name('password.forgot');
    Route::get('password/update/{code}/{email}', PasswordResetLivewire::class)->name('password.reset.link');
    Route::get('preview/share/{type}/{mId}', SharePreviewLivewire::class)->name('preview.share');

    // AUth routes
    Route::group(['middleware' => ['auth', 'restrict_roles:client,driver', "user.active.check"]], function () {

        //
        Route::get('profile', ProfileLivewire::class)->name('profile');
        Route::get('dashboard', DashboardLivewire::class)->name('dashboard');
        Route::get('', function () {
            return redirect()->route('dashboard');
        });
        Route::get('product/products', ProductLivewire::class)->name('products');
        Route::get('product/details/{id}', ProductDetailsLivewire::class)->name('product.details');
        Route::get('product/requests', ProductRequestLivewire::class)->name('products.requests');
        Route::get('product/menus', MenuLivewire::class)->name('products.menus');
        Route::get('product/options/group', OptionGroupLivewire::class)->name('products.options.group');
        Route::get('product/options', OptionLivewire::class)->name('products.options');

        //services
        Route::get('service/services', ServiceLivewire::class)->name('services');
        Route::get('service/option/groups', ServiceOptionGroupLivewire::class)->name('services.option.groups');
        Route::get('service/options', ServiceOptionLivewire::class)->name('services.options');

        Route::get('order/orders', OrderLivewire::class)->name('orders');
        Route::get('order/create', NewOrderLivewire::class)->name('order.create');
        Route::get('order/edit/order/{code}/products', EditOrderLivewire::class)->name('order.edit.products');
        Route::get('order/refunds', RefundLivewire::class)->name('refunds');
        Route::get('order/coupons', CouponLivewire::class)->name('coupons');

        Route::get('modules', VendorTypeLivewire::class)->name('vendor.types');
        Route::get('vendors', VendorLivewire::class)->name('vendors');
        Route::get('vendor/details/{id}', VendorDetailsLivewire::class)->name('vendor.details');

        //admin/manager routes
        Route::get('earnings/drivers', DriverEarningLivewire::class)->name('earnings.drivers');
        Route::get('earnings/remittance', DriverRemittanceLivewire::class)->name('earnings.remittance');
        Route::get('payouts', PayoutLivewire::class)->name('payouts');
        Route::get('payments/accounts', PaymentAccountLivewire::class)->name('payment.accounts');


        //admin routes
        Route::group(['middleware' => ['role:admin']], function () {

            //
            Route::get('operations/cron/job', CronJobLivewire::class)->name('configure.cron.job');
            Route::get('operations/order/assignment', AutoAssignmentLivewire::class)->name('auto.assignments');
            Route::get('operations/troubleshooting', TroubleShootLivewire::class)->name('troubleshooting');

            Route::get('banners', BannerLivewire::class)->name('banners');
            Route::get('tags', TagLivewire::class)->name('tags');
            Route::get('tags/link/{id}', TagLinkLivewire::class)->name('tags.link');
            Route::get('categories', CategoryLivewire::class)->name('categories');
            Route::get('categories/subcategories', SubCategoryLivewire::class)->name('subcategories');

            Route::get('product/favourites', FavouriteLivewire::class)->name('favourites');
            Route::get('order/reviews', ReviewLivewire::class)->name('reviews');
            Route::get('order/product/reviews', ProductReviewLivewire::class)->name('products.reviews');
            // Route::get('order/delivery/addresses', DeliveryAddressLivewire::class)->name('delivery.addresses');

            Route::get('payments/wallet/transactions', WalletTransactionLivewire::class)->name('wallet.transactions');

            //
            Route::get('setting/currencies', CurrencyLivewire::class)->name('currencies');
            Route::get('setting/settings', SettingsLivewire::class)->name('settings');
            Route::get('setting/page', PageSettingsLivewire::class)->name('settings.page');
            Route::get('setting/cms/page', CMSPageSettingsLivewire::class)->name('settings.page.cms');
            Route::get('setting/app/settings', AppSettingsLivewire::class)->name('settings.app');
            Route::get('setting/app/map', MapSettingsLivewire::class)->name('settings.map');
            Route::get('setting/ui/settings', UISettingsLivewire::class)->name('settings.ui');
            Route::get('setting/finance/settings', FinanceSettingsLivewire::class)->name('settings.finance');
            Route::get('setting/website/settings', WebsiteSettingsLivewire::class)->name('settings.website');
            Route::get('setting/app/server', ServerSettingsLivewire::class)->name('settings.server');
            Route::get('setting/payment/methods', PaymentMethodivewire::class)->name('payment.methods');
            Route::get('setting/wallet/payment/methods', WalletPaymentMethodLivewire::class)->name('wallet.payment.methods');
            Route::get('setting/translation', TranslationLivewire::class)->name('translation');
            Route::get('setting/app/upgrade', AppUpgradeSettingsLivewire::class)->name('settings.app.upgrade');
            Route::get('setting/upgrade', UpgradeLivewire::class)->name('upgrade');
            Route::get('setting/dynamic/link', DynamicLinkSettingsLivewire::class)->name('settings.dynamic.link');
            Route::get('setting/payment/webhooks', WebhookSettingsLivewire::class)->name('settings.webhooks');

            //package
            Route::get('package/types', PackageTypeLivewire::class)->name('package.types');
            Route::get('package/countries', CountryLivewire::class)->name('package.countries');
            Route::get('package/states', StateLivewire::class)->name('package.states');
            Route::get('package/cities', CitiesLivewire::class)->name('package.cities');
            Route::get('package/order/new', NewPackageOrderLivewire::class)->name('package.order.new');


            //imports
            Route::get('operations/notification/send', NotificationLivewire::class)->name('notification.send');
            Route::get('operations/imports', ImportLivewire::class)->name('imports');
            Route::get('operations/exports', ExportLivewire::class)->name('exports');
            Route::get('operations/backup', BackUpLivewire::class)->name('backups');
            Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
            Route::get('operations/data/clear', DataLivewire::class)->name('data.clear');
            // Route::get('jobs/monitor', '\romanzipp\QueueMonitor\Controllers\ShowQueueMonitorController')->name('job-monitor');
            Route::prefix('jobs')->group(function () {
                // Route::get('', '\romanzipp\QueueMonitor\Controllers\ShowQueueMonitorController')->name('queue-monitor::index');
                // Route::delete('monitors/{monitor}', '\romanzipp\QueueMonitor\Controllers\DeleteMonitorController')->name('queue-monitor::destroy');
                // Route::delete('purge', '\romanzipp\QueueMonitor\Controllers\PurgeMonitorsController')->name('queue-monitor::purge');
            });

            //settings
            Route::get('setting/sms/gateways', SMSGatewayLivewire::class)->name('sms.settings');
            Route::get('setting/roles', RoleManagerLivewire::class)->name('settings.roles');
            //subscription
            Route::get('subscription/subscriptions', SubscriptionLivewire::class)->name('subscriptions');
            Route::get('reports/subscriptions', SubscriptionReportLivewire::class)->name('reports.subscriptions');
        });

        //Taxi booking
        Route::get('taxi/vehicle/types', VehicleTypeLivewire::class)
            ->name('taxi.vehicle.types')
            ->middleware(['permission:view-taxi-vehicle-types']);

        Route::get('taxi/order/new', NewTaxiOrderLivewire::class)
            ->name('taxi.order.new')
            ->middleware(['permission:new-taxi-order']);

        Route::get('taxi/vehicles', VehicleLivewire::class)
            ->name('taxi.vehicles')
            ->middleware(['permission:view-taxi-vehicles']);

        Route::get('taxi/car/makes', CarMakeLivewire::class)
            ->name('taxi.car.makes')
            ->middleware(['permission:view-car-makes']);

        Route::get('taxi/car/models', CarModelLivewire::class)
            ->name('taxi.car.models')
            ->middleware(['permission:view-car-models']);

        Route::get('taxi/payment/methods', PaymentMethodVehicleTypeLivewire::class)
            ->name('taxi.payment.methods')
            ->middleware(['permission:view-taxi-payment-methods']);

        Route::get('taxi/settings', TaxiSettingLivewire::class)
            ->name('taxi.settings')
            ->middleware(['permission:view-taxi-settings']);

        Route::get('taxi/pricing', TaxiPricingLivewire::class)
            ->name('taxi.pricing')
            ->middleware(['permission:view-taxi-zones']);

        Route::get('taxi/zones', TaxiZoneLivewire::class)
            ->name('taxi.zones')
            ->middleware(['permission:view-users']);




        //Location Zone mangt.
        Route::get('vendors/zones', DeliveryZoneLivewire::class)
            ->name('zones')
            ->middleware(['permission:view-users']);



        Route::get('users', UserLivewire::class)
            ->name('users')
            ->middleware(['permission:view-users']);
        //
        Route::get('users/{id}/permissions', UserPermissionLivewire::class)
            ->name('users.assign-permissions')
            ->middleware(['permission:assign-permissions']);
        //
        Route::get('users/{id}/details', UserDetailsLivewire::class)
            ->name('users.details')
            ->middleware(['permission:view-users']);

        Route::get('users/deleted', DeletedUserLivewire::class)
            ->name('users.deleted')
            ->middleware(['permission:view-deleted-users']);

        Route::get('order/delivery/addresses', DeliveryAddressLivewire::class)
            ->name('delivery.addresses')
            ->middleware(['permission:view-delivery-addresses']);

        Route::get('earnings/vendors', VendorEarningLivewire::class)
            ->name('earnings.vendors')
            ->middleware(['permission:view-earning']);

        Route::get('extensions', ExtensionLivewire::class)
            ->name('extensions')
            ->middleware(['permission:manage-extensions']);

        //subscription
        Route::get('subscription/vendors/subscriptions', VendorSubscriptionLivewire::class)
            ->name('vendors.subscriptions')
            ->middleware(['permission:view-subscription']);


        //report
        Route::get('reports/coupons', CouponReportLivewire::class)
            ->name('reports.coupons')
            ->middleware(['permission:view-coupon-report']);

        //manager routes
        Route::group(['middleware' => ['role:manager']], function () {

            Route::get('package/pricing', PackageTypePricingLivewire::class)->name('package.pricing');
            Route::get('package/my/cities', VendorCitiesLivewire::class)->name('package.cities.my');
            Route::get('package/my/states', VendorStatesLivewire::class)->name('package.states.my');
            Route::get('package/my/countries', VendorCountriesLivewire::class)->name('package.countries.my');
            Route::get('vendor/drivers', DriverLivewire::class)->name('my.drivers');
            Route::get('vendor/driver/settings', VendorDriverSettingLivewire::class)->name('my.driver.settings');
            Route::get('vendor/payment/methods', VendorPaymentMethodLivewire::class)->name('payment.methods.my');
            //subscription
            Route::get('subscription/my', MySubscriptionLivewire::class)->name('my.subscriptions');
            Route::get('subscription/my/subscribe', SubscribeLivewire::class)->name('my.subscribe');

            //
            Route::get('service/my/cities', VendorCitiesLivewire::class)->name('service.cities.my');
            Route::get('service/my/states', VendorStatesLivewire::class)->name('service.states.my');
            Route::get('service/my/countries', VendorCountriesLivewire::class)->name('service.countries.my');
            //Payouts
            Route::get('payments/my/payouts', MyPayoutLivewire::class)->name('my.payouts');
            Route::get('package/vendor/order/new', VendorNewPackageOrderLivewire::class)->name('vendor.package.order.new');
        });


        //report
        //Reports
        Route::get('reports/products', ProductReportLivewire::class)->name('reports.products')
            ->middleware(['permission:view-product-report']);
        Route::get('reports/services', ServiceReportLivewire::class)
            ->name('reports.services')
            ->middleware(['permission:view-service-report']);
        Route::get('reports/vendors', VendorReportLivewire::class)
            ->name('reports.vendors')
            ->middleware(['permission:view-vendor-report']);
        Route::get('reports/customers', CustomerReportLivewire::class)
            ->name('reports.customers')
            ->middleware(['permission:view-customers-report']);
        Route::get('reports/referral', ReferralReportLivewire::class)
            ->name('reports.referral')
            ->middleware(['permission:view-referral-report']);
        Route::get('reports/commission', CommissionReportLivewire::class)
            ->name('reports.commission')
            ->middleware(['permission:view-commission-report']);

        //fleet management
        Route::group(['middleware' => ['role_or_permission:admin|fleet-manager|manage-fleet']], function () {

            Route::get('fleet/list', FleetsLivewire::class)->name('fleets');
            Route::get('fleet/users', FleetUsersLivewire::class)->name('fleet.users');
            Route::get('fleet/vehicles', FleetVehiclesLivewire::class)->name('fleet.vehicles');
            Route::get('fleet/report', FleetOrderLivewire::class)->name('fleet.report');
        });




        //permission base routes
        Route::get('flash/sales', FlashSaleLivewire::class)->name('flash.sales')
            ->middleware(['permission:view-flash-sales']);
        Route::get('earnings/my', MyEarningLivewire::class)->name('my.earnings')
            ->middleware(['permission:my-earning']);
        Route::get('setting/inapp/support', InAppSupportLivewire::class)->name('inapp.support')
            ->middleware(['permission:view-in-app-support']);
        Route::get('reports/summary', SummaryReportLivewire::class)->name('reports.summary')
            ->middleware(['permission:view-summary-report']);
        Route::get('reports/loyalty', LoyaltyReportLivewire::class)->name('reports.loyalty')
            ->middleware(['permission:view-loyalty']);
        Route::get('misc/onboarding', OnboardingLivewire::class)->name('onboarding')
            ->middleware(['permission:mang-onboarding']);
        Route::get('misc/faqs', FaqLivewire::class)->name('faqs')
            ->middleware(['permission:view-faq']);
        Route::get('vendors/fees', VendorFeesLivewire::class)->name('vendor.fees')
            ->middleware(['permission:set-vendor-fees']);

        Route::get('order/chat/{code}', OrderChatLivewire::class)->name('order.chats')
            ->middleware(['permission:view-order-chat']);
        Route::get('order/print/{code}', OrderPrintLivewire::class)->name('order.print')
            ->middleware(['permission:view-print-order']);
        // earning history
        Route::get('earnings/driver/history', DriverEarningHistoryLivewire::class)->name('driver.earnings.history')
            ->middleware(['permission:driver-earning-history']);
        Route::get('earnings/vendor/history', VendorEarningHistoryLivewire::class)->name('vendor.earnings.history')
            ->middleware(['permission:vendor-earning-history']);
        //
        Route::get('payments/outstanding', OutstandingPaymentLivewire::class)->name('payment.outstanding')
            ->middleware(['permission:view-outstanding-payments']);

        // driver management
        Route::get('drivers', SystemDriverLivewire::class)->name('drivers')
            ->middleware(['permission:view-drivers']);
        Route::get('driver/incentives', OutstandingPaymentLivewire::class)->name('driver.incentives')
            ->middleware(['permission:driver.incentives']);

        //document management
        Route::get('vendors/documents', VendorDocumentRequestLivewire::class)->name('vendors.documents')
            ->middleware(['permission:view-vendor-documents']);
        Route::get('drivers/documents', DriverDocumentRequestLivewire::class)->name('drivers.documents')
            ->middleware(['permission:view-driver-documents']);
    });



    //Unauth routes
    Route::get('payment/processed', PaymentProcessedLivewire::class)->name('payment.processed');
    Route::get('order/payment', OrderPaymentLivewire::class)->name('order.payment');
    Route::get('order/payment/callback', OrderPaymentCallbackLivewire::class)->name('payment.callback');
    //Wallet
    Route::get('wallet/topup/payment/{code}', WalletTopUpLivewire::class)->name('wallet.topup');
    // Route::get('wallet/topup/failed', WalletTopUpFailureLivewire::class)->name('wallet.topup.failed');
    Route::get('wallet/topup/failed/{code}', WalletTopUpFailureLivewire::class)->name('wallet.topup.failed');
    // Route::get('wallet/topup/callback', WalletTopUpCallbackLivewire::class)->name('wallet.topup.callback');
    Route::get('wallet/topup/callback/{code}', WalletTopUpCallbackLivewire::class)->name('wallet.topup.callback');

    //Subscription callback
    // Route::get('subscription/payment/callback', SubscribeCallbackLivewire::class)->name('subscription.callback');
    Route::get('subscription/payment/callback/{code}', SubscribeCallbackLivewire::class)->name('subscription.callback');
});