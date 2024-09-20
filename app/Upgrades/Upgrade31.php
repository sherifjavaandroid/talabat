<?php

namespace App\Upgrades;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade31 extends BaseUpgrade
{

    public $versionName = "1.4.7";
    //Runs or migrations to be done on this version
    public function run()
    {


        //nav_menus
        if (!Schema::hasTable('nav_menus')) {
            Artisan::call('migrate --path=database/migrations/2022_01_22_231311_create_nav_menus_table.php --force');
        }
        if (!Schema::hasColumn('nav_menus', "roles")) {
            Schema::table('nav_menus', function (Blueprint $table) {
                $table->string('roles')->after('route')->nullable();
            });
        }


        Schema::table('banners', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();
            $table->foreignId('vendor_id')->nullable()->change();
        });

        if (!Schema::hasTable('earneds')) {
            Artisan::call('migrate --path=database/migrations/2022_01_27_093913_create_earneds_table.php --force');
        }

        //update driver updated_at value so, the vehicle_type_id is synced with firestore
        try {
            User::role('driver')->get()->each(function ($driver) {
                $driver->updated_at = \Carbon\Carbon::now();
                $driver->save();
            });
        } catch (\Exception $ex) {
            logger("Upgrade error", [$ex]);
        }


        //file data
        if (\App::environment('production')) {
            //privacyPolicy
            $filePath = base_path() . "/resources/views/layouts/includes/privacy.blade.php";
            file_put_contents($filePath, setting('privacyPolicy', ''));
            //contactInfo
            $filePath = base_path() . "/resources/views/layouts/includes/contact.blade.php";
            file_put_contents($filePath, setting('contactInfo', ''));
            //terms
            $filePath = base_path() . "/resources/views/layouts/includes/terms.blade.php";
            file_put_contents($filePath, setting('terms', ''));

            //forge the values
            setting()->forget('privacyPolicy');
            setting()->forget('contactInfo');
            setting()->forget('terms');
        }


        // allow vendors created own drivers
        if (!Schema::hasColumn('vendors', "has_drivers")) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->boolean('has_drivers')->after('use_subscription')->default(false);
            });
        }
    }
}
