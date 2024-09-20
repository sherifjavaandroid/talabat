<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;

class Upgrade49 extends BaseUpgrade
{

    public $versionName = "1.6.5";
    //Runs or migrations to be done on this version
    public function run()
    {


        //add featured to vendors
        if (!Schema::hasColumn('products', 'barcode')) {
            Schema::table("products", function ($table) {
                $table->string('barcode')->nullable()->after("sku");
            });
        }
        //
        if (!Schema::hasColumn('earning_reports', 'order_id')) {
            Schema::table("earning_reports", function ($table) {
                $table->foreignId('order_id')->nullable()->constrained()->after("earning_id");
                $table->string('rate')->nullable()->after("balance");
            });
        }

        if (!Schema::hasColumn('coupons', 'color')) {
            Schema::table("coupons", function ($table) {
                $table->string('color')->default(setting('appColorTheme.primaryColor', '#21a179') ?? "#000000")->after("code");
            });
        }
    }
}
