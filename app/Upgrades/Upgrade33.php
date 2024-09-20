<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade33 extends BaseUpgrade
{

    public $versionName = "1.5.0";
    //Runs or migrations to be done on this version
    public function run()
    {

        //add prepare time to vendor
        if (!Schema::hasColumn("orders", 'payer')) {
            Schema::table("orders", function ($table) {
                $table->boolean('payer')->default(true)->after('height');
            });
        }

        if (!Schema::hasTable('referrals')) {
            Artisan::call('migrate --path=database/migrations/2022_02_25_003453_create_referrals_table.php --force');
        }

        if (!Schema::hasTable('commissions')) {
            Artisan::call('migrate --path=database/migrations/2022_02_25_032814_create_commissions_table.php --force');
        }

        //
        if (!Schema::hasColumn("coupons", 'min_order_amount')) {
            Schema::table("coupons", function ($table) {
                $table->double('max_coupon_amount', 8, 2)->after('discount')->nullable();
                $table->double('min_order_amount', 8, 2)->after('discount')->nullable();
            });
        }
    }


}
