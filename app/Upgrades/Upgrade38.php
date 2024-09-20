<?php

namespace App\Upgrades;

use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade38 extends BaseUpgrade
{

    public $versionName = "1.5.5";
    //Runs or migrations to be done on this version
    public function run()
    {


        if (!Schema::hasColumn("users", 'language')) {
            Schema::table("users", function ($table) {
                $table->string('language')->default("en")->after('creator_id');
            });
        }

        if (!Schema::hasColumn('payment_methods', 'use_wallet')) {
            Schema::table('payment_methods', function ($table) {
                $table->boolean('use_wallet')->default(true)->after("use_taxi");
            });
            //update the is_active with the use_wallet
            $paymentMethods = PaymentMethod::all();
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethod->use_wallet = $paymentMethod->is_active;
                $paymentMethod->save();
            }
        }

        if (!Schema::hasColumn('orders', 'tax_rate')) {
            Schema::table('orders', function ($table) {
                $table->double('tax_rate', 15, 2)->default(0)->after("tax");
            });

            //update the tax_rate
            Order::where('id', '>', 0)
                ->update(['tax_rate' => \DB::raw('(`tax` / `sub_total`) * 100')]);
        }

        if (!Schema::hasTable('refunds')) {
            Artisan::call('migrate --path=database/migrations/2022_04_28_090529_create_refunds_table.php --force');
        }
    }
}
