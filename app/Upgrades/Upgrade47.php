<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade47 extends BaseUpgrade
{

    public $versionName = "1.6.3";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasTable('loyalty_points')) {
            Artisan::call('migrate --path=database/migrations/2022_11_29_134719_create_loyalty_points_table.php --force');
        }

        if (!Schema::hasTable('loyalty_point_reports')) {
            Artisan::call('migrate --path=database/migrations/2022_11_30_004701_create_loyalty_point_reports_table.php --force');
        }
    }
}
