<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade36 extends BaseUpgrade
{

    public $versionName = "1.5.3";
    //Runs or migrations to be done on this version
    public function run()
    {

        Artisan::call('db:seed --class=PermissionsTableSeeder --force');
        
        if (!\Schema::hasColumn('remittances', 'earned')) {
            Schema::table("remittances", function ($table) {
                $table->double('earned', 10, 2)->default(0.00)->nullable()->after('order_id');
            });
        }
    }

}
