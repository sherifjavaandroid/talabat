<?php

namespace App\Providers;

use App\Listeners\OrderStatusEventSubscriber;
use App\Models\AutoAssignment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Order;
use App\Models\PackageType;
use App\Models\SubscriptionVendor;
use App\Models\Vehicle;
use App\Models\Payout;
use App\Models\Product;
use App\Models\Service;
use App\Models\TaxiOrder;
use App\Models\Vendor;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Observers\AutoAssignmentObserver;
//
use App\Observers\OrderObserver;
use App\Observers\OrderFeesObserver;
use App\Observers\PackageTypeObserver;
use App\Observers\SubscriptionObserver;
//
use App\Observers\TaxiDriverObserver;
use App\Observers\TaxiOrderObserver;
use App\Observers\TaxiOrderTripObserver;
use App\Observers\VehicleObserver;
use App\Observers\PayoutObserver;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Observers\VendorObserver;
use App\Observers\ReferralObserver;
use App\Observers\WalletObserver;
use App\Observers\WalletTransactionObserver;
use App\Observers\OrderLoyaltyObserver;
use App\Observers\OverdraftOrderObserver;
use App\Observers\OverdraftWalletObserver;
use App\Observers\VendorOpenObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $subscribe = [
        OrderStatusEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
        User::observe(UserObserver::class);
        Vendor::observe(VendorObserver::class);
        Vendor::observe(VendorOpenObserver::class);
        Order::observe(OrderObserver::class);
        Order::observe(OrderFeesObserver::class);
        SubscriptionVendor::observe(SubscriptionObserver::class);
        Payout::observe(PayoutObserver::class);

        //Majorly for taxi
        User::observe(TaxiDriverObserver::class);
        Order::observe(TaxiOrderObserver::class);
        TaxiOrder::observe(TaxiOrderTripObserver::class);
        Vehicle::observe(VehicleObserver::class);
        Order::observe(OverdraftOrderObserver::class);

        //Subscription qty checks
        Product::observe(ProductObserver::class);
        Service::observe(ServiceObserver::class);
        PackageType::observe(PackageTypeObserver::class);
        //
        Order::observe(ReferralObserver::class);
        Order::observe(OrderLoyaltyObserver::class);
        Wallet::observe(OverdraftWalletObserver::class);
        Wallet::observe(WalletObserver::class);
        WalletTransaction::observe(WalletTransactionObserver::class);
        //add wallet transaction observer
        AutoAssignment::observe(AutoAssignmentObserver::class);

        // translations observer
        \App\Models\Product::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Category::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Coupon::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Fee::observe(\App\Observers\TranslationObserver::class);
        \App\Models\PackageType::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Menu::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Onboarding::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Service::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Subcategory::observe(\App\Observers\TranslationObserver::class);
        \App\Models\Tag::observe(\App\Observers\TranslationObserver::class);
        \App\Models\VendorType::observe(\App\Observers\TranslationObserver::class);
    }
}
