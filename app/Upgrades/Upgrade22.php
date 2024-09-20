<?php

namespace App\Upgrades;

use App\Models\PaymentMethod;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade22 extends BaseUpgrade
{

    public $versionName = "1.3.8";
    //Runs or migrations to be done on this version
    public function run()
    {

        //
        if (!Schema::hasColumn('coupon_user', 'order_id')) {
            Schema::table('coupon_user', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->constrained();
            });
        }

        //  adding link to banners
        if (!Schema::hasColumn('banners', 'link')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->string('link')->nullable()->after("id");
            });
            Schema::table('banners', function (Blueprint $table) {
                $table->foreignId('vendor_id')->after("category_id")->nullable()->constrained();
            });
        }

        //
        if (!Schema::hasColumn('order_products', 'options_ids')) {
            Schema::table('order_products', function (Blueprint $table) {
                $table->string('options_ids')->nullable()->after("options");
            });
        }

        //
        if (!Schema::hasColumn('payment_methods', 'class')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->string('class')->nullable()->after("hash_key");
            });
        }

        //paytm
        $paymetnMethod = PaymentMethod::where('slug', "paytm")->first();
        if (empty($paymetnMethod)) {
            \DB::table('payment_methods')->insert(array(
                0 =>
                array(
                    'name' => 'PayTm',
                    'slug' => 'paytm',
                    'instruction' => NULL,
                    'secret_key' => '',
                    'public_key' => '',
                    'hash_key' => NULL,
                    'class' => NULL,
                    'is_active' => 1,
                    'is_cash' => 0,
                    'created_at' => '2021-01-09 12:38:10',
                    'updated_at' => '2021-09-10 22:16:00',
                    'deleted_at' => NULL,
                ),
            ));
        }
        //payu
        $paymetnMethod = PaymentMethod::where('slug', "payu")->first();
        if (empty($paymetnMethod)) {
            \DB::table('payment_methods')->insert(array(
                0 =>
                array(
                    'name' => 'PayU',
                    'slug' => 'payu',
                    'instruction' => NULL,
                    'secret_key' => '',
                    'public_key' => '',
                    'hash_key' => NULL,
                    'class' => NULL,
                    'is_active' => 1,
                    'is_cash' => 0,
                    'created_at' => '2021-01-09 12:38:10',
                    'updated_at' => '2021-09-10 22:16:00',
                    'deleted_at' => NULL,
                ),
            ));
        }


        //
        if (!Schema::hasTable('services')) {
            Artisan::call('migrate --path=database/migrations/2021_09_23_111546_create_services_table.php --force');
        }
        //
        if (!Schema::hasTable('order_services')) {
            Artisan::call('migrate --path=database/migrations/2021_09_25_135154_create_order_services_table.php --force');
        }
    }

}
