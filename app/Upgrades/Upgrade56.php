<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade56 extends BaseUpgrade
{

    public $versionName = "1.6.64";
    //Runs or migrations to be done on this version
    public function run()
    {
        //modify vendors table, make column commission nullable
        Schema::table('vendors', function ($table) {
            $table->decimal('commission', 10, 2)->default(null)->nullable()->change();
        });
        //
        //migrate the fcm_notifications table
        //check if table exists
        if (!Schema::hasTable('firebase_notifications')) {
            Artisan::call('migrate --path=database/migrations/2023_06_14_195135_create_firebase_notifications_table.php --force');
        }
    }
}
