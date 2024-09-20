<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;

class Upgrade46 extends BaseUpgrade
{

    public $versionName = "1.6.2";
    //Runs or migrations to be done on this version
    public function run()
    {

        //subcategory_id to services
        if (Schema::hasColumn("services", 'subcategory_id')) {
            Schema::disableForeignKeyConstraints();
            Schema::table("services", function ($table) {
                $table->foreignId('subcategory_id')->nullable()->change();
            });
            Schema::enableForeignKeyConstraints();
        }
    }
}
