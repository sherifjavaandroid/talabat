<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tag;
use App\Models\VendorType;

class Upgrade40 extends BaseUpgrade
{

    public $versionName = "1.5.7";
    //Runs or migrations to be done on this version
    public function run()
    {


        if (!Schema::hasTable('product_reviews')) {
            Artisan::call('migrate --path=database/migrations/2022_05_29_154800_create_product_reviews_table.php --force');
        }
       
    }
}
