<?php

namespace App\Upgrades;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\DriverType;
use App\Models\Fee;
use App\Models\Menu;
use App\Models\Onboarding;
use App\Models\PackageType;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VendorType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Upgrade50 extends BaseUpgrade
{

    public $versionName = "1.6.6";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasTable('outstanding_balances')) {
            Artisan::call('migrate --path=database/migrations/2023_01_29_174121_create_outstanding_balances_table.php --force');
        }

        if (!Schema::hasTable('driver_types')) {
            Artisan::call('migrate --path=database/migrations/2023_02_02_204717_create_driver_types_table.php --force');
        }
        //set driver types
        $drivers = User::role('driver')->get();
        foreach ($drivers as $driver) {
            if (empty($driver->vehicles)) {
                DriverType::firstOrCreate(["driver_id" => $driver->id], ["is_taxi" => 0]);
            } else {
                DriverType::firstOrCreate(["driver_id" => $driver->id], ["is_taxi" => 1]);
            }
        }

        //add verified to vehicle table
        if (!Schema::hasColumn('vehicles', 'verified')) {
            Schema::table("vehicles", function ($table) {
                $table->boolean('verified')->default(false)->after("is_active");
            });
        }
        //update previouse vehicle data
        $vehicles = Vehicle::get();
        foreach ($vehicles as $vehicle) {
            $vehicle->verified = $vehicle->is_active ?? false;
            $vehicle->saveQuietly();
        }


        //ALTER SERVICES TO ALLOW NULLABLE subcategory_id
        DB::statement("ALTER TABLE `services` CHANGE `subcategory_id` `subcategory_id` BIGINT(20) UNSIGNED NULL");

        //translate models
        $tables = ["vendor_types", "products", "fees", "menus", "categories", "subcategories", "onboardings", "services", "tags", "package_types", "coupons"];
        $columns = ["title", 'name', "description"];
        $columnShifteds = ["title_shifted", 'name_shifted', "description_shifted"];
        $models = [
            VendorType::class, Product::class, Fee::class, Menu::class, Category::class, Subcategory::class, Onboarding::class, Service::class,
            Tag::class, PackageType::class, Coupon::class
        ];

        //
        foreach ($tables as $key => $table) {
            $model = $models[$key];

            foreach ($columns as $cKey => $column) {
                $columnShifted = $columnShifteds[$cKey];
                //
                if (Schema::hasColumn($table, $column)) {

                    //check if table column is already translated
                    $foundModel = $model::first();
                    $columnValue = $foundModel != null ? ($foundModel->getRawOriginal($column) ?? "") : "";
                    $alreadyTranslated = !empty($columnValue) && (strpos($columnValue, "{\"en\":") !== false);

                    if ($alreadyTranslated) {
                        logger("Already translated", [$table, $column]);
                        continue;
                    }


                    Schema::table($table, function ($table) use ($column, $columnShifted) {
                        $table->text($column)->change();
                    });
                    //add shited column if not exist
                    if (!Schema::hasColumn($table, $columnShifted)) {
                        Schema::table($table, function ($table) use ($columnShifted) {
                            $table->text($columnShifted);
                        });
                        logger("table create column", [$table, $columnShifted]);
                    } else {
                        logger("table has column", [$table, $columnShifted]);
                    }

                    //copy from $cloumn to $columnShifted
                    $model::whereNotNull('id')->update([
                        $columnShifted => DB::raw($column),
                    ]);


                    //copy the translate formated value back to right column
                    $model::whereNotNull('id')->update([
                        $column => DB::raw("CONCAT('{\"en\":\"',$columnShifted,'\"}')"),
                    ]);


                    //delete shifted column
                    Schema::table($table, function ($table) use ($columnShifted) {
                        $table->dropColumn($columnShifted);
                    });
                }
            }

            //
        }
    }
}
