<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;

class Upgrade63 extends BaseUpgrade
{

    public $versionName = "1.7.20";
    //Runs or migrations to be done on this version
    public function run()
    {

        //add delivery_fee to delivery_zones table
        if (!Schema::hasColumn('delivery_zones', 'delivery_fee')) {
            Schema::table('delivery_zones', function ($table) {
                $table->double('delivery_fee')->nullable()->after('radius');
            });
        }
    }
}
