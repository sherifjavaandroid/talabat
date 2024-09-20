<?php

namespace App\Upgrades;

class Upgrade71 extends BaseUpgrade
{

    public $versionName = "1.7.44";
    //Runs or migrations to be done on this version
    public function run()
    {

        //if faqs table is empty, seed it
        if (\DB::table('faqs')->count() == 0) {
            \Artisan::call('db:seed --class=FaqsTableSeeder --force');
        }
    }
}
