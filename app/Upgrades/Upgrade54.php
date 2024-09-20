<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Upgrade54 extends BaseUpgrade
{

    public $versionName = "1.6.63";
    //Runs or migrations to be done on this version
    public function run()
    {
        //run migrations
        if (!Schema::hasTable('vendor_open_statuses')) {
            Artisan::call('migrate --path=database/migrations/2023_04_23_202759_create_vendor_open_statuses_table.php --force');
        }

        //to create firebase firestore indexes
        $firestoreRestService = new \App\Services\FirestoreRestService();
        $lat = 5.603716;
        $lng = -0.232861;
        $radius = 20;
        //taxi driver
        $vehicleType = \App\Models\VehicleType::active()->first();
        if ($vehicleType != null) {
            $vehicleTypeId = $vehicleType->id;
            $drivers = $firestoreRestService->whereWithinGeohash($lat, $lng, $radius, 5, $vehicleTypeId);
        }
        // logger("Taxi Drivers", [$drivers]);
        //regular drivers
        $drivers = $firestoreRestService->whereWithinGeohash($lat, $lng, $radius);
        // logger("Regular Drivers", [$drivers]);


        //for services to have options
        if (!Schema::hasTable('service_option_groups')) {
            Artisan::call('migrate --path=database/migrations/2023_05_06_171149_create_service_option_groups_table.php --force');
        }
        if (!Schema::hasTable('service_options')) {
            Artisan::call('migrate --path=database/migrations/2023_05_06_171324_create_service_options_table.php --force');
        }
        if (!Schema::hasTable('option_service')) {
            Artisan::call('migrate --path=database/migrations/2023_05_06_175338_option_service.php --force');
        }

        //add missing columns to order services table
        if (!Schema::hasColumn('order_services', 'options')) {
            Schema::table('order_services', function ($table) {
                $table->text('options')->nullable();
                $table->string('options_ids')->nullable();
            });
        }

        //migrate the missing table vendor_type_delivery_zone
        if (!Schema::hasTable('delivery_zone_vendor_type')) {
            Artisan::call('migrate --path=database/migrations/2023_05_08_201522_create_delivery_zone_vendor_type_pivot_table.php --force');
        }
    }
}
