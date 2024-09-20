<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use GeoSot\EnvEditor\Facades\EnvEditor;

class Upgrade43 extends BaseUpgrade
{

    public $versionName = "1.6.0";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasTable('onboardings')) {
            Artisan::call('migrate --path=database/migrations/2022_08_15_113549_create_onboardings_table.php --force');
        }

        if (!Schema::hasTable('fees')) {
            Artisan::call('migrate --path=database/migrations/2022_08_21_204107_create_fees_table.php --force');
        }

        if (!Schema::hasTable('fee_vendor')) {
            Artisan::call('migrate --path=database/migrations/2022_08_22_071713_create_fee_vendor_pivot_table.php --force');
        }


        if (!Schema::hasColumn("orders", 'fees')) {
            Schema::table("orders", function ($table) {
                $table->text('fees')->nullable()->after('tax_rate');
            });
        }

        if (!Schema::hasColumn("products", 'digital')) {
            Schema::table("products", function ($table) {
                $table->boolean('digital')->default(false)->after('plus_option');
            });
        }


        //Migrate settings from db to json file
        // if (env("APP_SETTINGS_LOC", 'database') == "database") {
        //     //
        //     if (!EnvEditor::keyExists("APP_SETTINGS_LOC")) {
        //         EnvEditor::addKey("APP_SETTINGS_LOC", "json");
        //     } else {
        //         EnvEditor::editKey("APP_SETTINGS_LOC", "json");
        //     }
        //     //
        //     $settings = \DB::table('settings')->options(['key', 'value'])->get();

        //     $values = [];
        //     foreach ($settings as $setting) {
        //         $values[$setting->key] = $setting->value;
        //     }

        //     config(['settings.store' => env("APP_SETTINGS_LOC", 'json')]);
        //     setting($values)->save();
        // }
    }
}
