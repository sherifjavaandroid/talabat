<?php

namespace App\Upgrades;

use App\Models\VendorType;
use App\Models\Order;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade23 extends BaseUpgrade
{

    public $versionName = "1.3.9";
    //Runs or migrations to be done on this version
    public function run()
    {

        //rename model status to old_status
        if (Schema::hasColumn('orders', 'status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('status', 'old_status');
            });

            //set the old_status to use the statuses model
            $orders = Order::get();
            foreach ($orders as $order) {
                $order->setStatus($order->old_status);
            }

            //drop the status cloumn
            if (Schema::hasColumn('orders', 'old_status')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('old_status');
                });
            }
        }
        
        //taxi vendor type is added
        $vendorType = VendorType::where('slug', "taxi")->first();
        if (empty($vendorType)) {
            \DB::table('vendor_types')->insert(array(
                0 =>
                array(
                    'name' => 'Taxi Booking',
                    'color' => '#000000',
                    'description' => 'For booking taxi',
                    'slug' => 'taxi',
                    'is_active' => 1,
                    'created_at' => '2021-07-15 00:38:10',
                    'updated_at' => '2021-09-24 01:08:57',
                    'deleted_at' => NULL,
                ),
            ));
        }

        //
        if (!Schema::hasTable('vehicle_types')) {
            Artisan::call('migrate --path=database/migrations/2021_09_30_151742_create_vehicle_types_table.php --force');
        }

        //
        if (!Schema::hasTable('car_makes')) {
            Artisan::call('migrate --path=database/migrations/2021_09_30_190439_create_car_makes_table.php --force');
            Artisan::call('db:seed --class=CarMakesTableSeeder --force');
        }
        //
        if (!Schema::hasTable('car_models')) {
            Artisan::call('migrate --path=database/migrations/2021_09_30_190451_create_car_models_table.php --force');
            Artisan::call('db:seed --class=CarModelsTableSeeder --force');
        }
        //
        if (!Schema::hasTable('vehicles')) {
            Artisan::call('migrate --path=database/migrations/2021_09_30_191128_create_vehicles_table.php --force');
        }
        //
        if (!Schema::hasTable('payment_method_vehicle_type')) {
            Artisan::call('migrate --path=database/migrations/2021_10_04_185924_create_payment_method_vehicle_type_pivot_table.php --force');
        }

        //
        if (!Schema::hasTable('taxi_orders')) {
            Artisan::call('migrate --path=database/migrations/2021_10_05_210851_create_taxi_orders_table.php --force');
        }

        //
        if (!Schema::hasColumn('payment_methods', 'use_taxi')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->boolean('use_taxi')->default(true)->after("is_cash");
            });
        }
        //change orders vendor_id
        \DB::statement("ALTER TABLE `orders` CHANGE `vendor_id` `vendor_id` BIGINT(20) UNSIGNED NULL");
    }

   
}
