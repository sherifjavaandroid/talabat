<?php

namespace App\Upgrades;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade18 extends BaseUpgrade
{   

    public $versionName = "1.3.3";
    //Runs or migrations to be done on this version
	public function run(){
        
        //product
        if (!Schema::hasColumn('products', 'plus_option')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('plus_option')->default(true)->after('is_active');
            });
        }

        //coupons
        if (!Schema::hasColumn('coupons', 'times')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->integer('times')->default(0)->after('is_active');
            });
        }

        //option groups
        if (!Schema::hasColumn('option_groups', 'required')) {
            Schema::table('option_groups', function (Blueprint $table) {
                $table->boolean('required')->default(false)->after('multiple');
            });
        }

         //force migration for new tables
         if (!Schema::hasTable('coupon_user')) {
            Artisan::call('migrate --path=database/migrations/2021_08_07_124104_create_coupon_user_pivot_table.php --force');
        }

        //vendor min/max orders
        if (!Schema::hasColumn('vendor_types', 'color')) {
            Schema::table('vendor_types', function (Blueprint $table) {
                $table->string('color')->default("#000")->after('name');
            });
        }

        //users
        if (!Schema::hasColumn('users', 'country_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country_code')->nullable()->after('phone');
            });
        }

    }
    

}