<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class Upgrade34 extends BaseUpgrade
{

    public $versionName = "1.5.1";
    //Runs or migrations to be done on this version
    public function run()
    {

        //add prepare time to vendor
        if (!Schema::hasColumn("banners", 'featured')) {
            Schema::table("banners", function ($table) {
                $table->integer('featured')->default(1)->after('in_order');
            });
        }

        //add fleet mamnager role 
        $fleetManagerRole = Role::where('name','fleet-manager')->first();
        if(empty($fleetManagerRole)){
            \DB::table('roles')->insert(array (
                0 => 
                array (
                    'name' => 'fleet-manager',
                    'guard_name' => 'web',
                ),
            ));
        }

        if (!Schema::hasTable('fleets')) {
            Artisan::call('migrate --path=database/migrations/2022_03_05_150200_create_fleets_table.php --force');
        }
        if (!Schema::hasTable('fleet_user')) {
            Artisan::call('migrate --path=database/migrations/2022_03_05_150757_create_fleet_user_pivot_table.php --force');
        }
        if (!Schema::hasTable('fleet_vehicle')) {
            Artisan::call('migrate --path=database/migrations/2022_03_05_225735_create_fleet_vehicle_pivot_table.php --force');
        }
        //
        Artisan::call('db:seed --class=PermissionsTableSeeder --force');

        //
        if (!Schema::hasColumn("package_types", 'driver_verify_stops')) {
            Schema::table("package_types", function ($table) {
                $table->boolean('driver_verify_stops')->default(false)->after('in_order');
            });
        }

        if (!Schema::hasColumn("order_stops", 'verified')) {
            Schema::table("order_stops", function ($table) {
                $table->boolean('verified')->default(false)->after('note');
            });
        }

        
    }


}
