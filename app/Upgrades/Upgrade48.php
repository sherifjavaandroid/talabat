<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade48 extends BaseUpgrade
{

    public $versionName = "1.6.4";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasTable('faqs')) {
            Artisan::call('migrate --path=database/migrations/2022_12_06_101410_create_faqs_table.php --force');
        }

        //add description to be nullable
        Schema::table("vendor_types", function ($table) {
            $table->text('description')->nullable()->change();
        });
        //add featured to vendors
        if (!Schema::hasColumn('vendors', 'featured')) {
            Schema::table("vendors", function ($table) {
                $table->integer('featured')->default(0);
            });
        }
    }
}
