<?php

namespace App\Upgrades;

use App\Models\Payout;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade26 extends BaseUpgrade
{

    public $versionName = "1.4.2";
    //Runs or migrations to be done on this version
    public function run()
    {

        //
        if (!Schema::hasColumn('push_notifications', 'product_id')) {
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->foreignId('product_id')->after('user_id')->nullable();
            });
        }
        if (!Schema::hasColumn('push_notifications', 'vendor_id')) {
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->foreignId('vendor_id')->after('product_id')->nullable();
            });
        }
        if (!Schema::hasColumn('push_notifications', 'service_id')) {
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->foreignId('service_id')->after('product_id')->nullable();
            });
        }
        if (!Schema::hasColumn('vendors', 'delivery_zone_id')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->foreignId('delivery_zone_id')->after('vendor_type_id')->nullable()->constrained();
            });
        }

        //
        if (!Schema::hasTable('delivery_zones')) {
            Artisan::call('migrate --path=database/migrations/2014_01_08_003220_create_delivery_zones_table.php --force');
        }

        //
        if (!Schema::hasTable('delivery_zone_points')) {
            Artisan::call('migrate --path=database/migrations/2014_01_08_003221_create_delivery_zone_points_table.php --force');
        }


        //change payment method to nullable for orders
        \DB::statement("ALTER TABLE `orders` CHANGE `payment_method_id` `payment_method_id` BIGINT(20) UNSIGNED NULL");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `payment_status` ENUM('pending','request', 'review', 'failed', 'cancelled', 'successful') DEFAULT 'pending'  NOT NULL");
       
    }


}
