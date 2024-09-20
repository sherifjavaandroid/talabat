<?php

namespace App\Upgrades;

class Upgrade73 extends BaseUpgrade
{

    public $versionName = "1.7.46";
    //Runs or migrations to be done on this version
    public function run()
    {

        //product_id to banners table
        if (!\Schema::hasColumn('banners', 'product_id')) {
            \Schema::table('banners', function ($table) {
                $table->foreignId('product_id')->nullable()->constrained()->after('vendor_id');
            });
        }
    }
}