<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tag;
use App\Models\VendorType;

class Upgrade39 extends BaseUpgrade
{

    public $versionName = "1.5.6";
    //Runs or migrations to be done on this version
    public function run()
    {


        if (!Schema::hasTable('tags')) {
            Artisan::call('migrate --path=database/migrations/2022_05_13_114506_create_tags_table.php --force');
        }
        if (!Schema::hasTable('product_tag')) {
            Artisan::call('migrate --path=database/migrations/2022_05_13_114736_create_product_tag_pivot_table.php --force');
        }

        //
        $foodVendorType = VendorType::whereSlug("food")->first();
        //
        Tag::firstOrCreate([
            "name" => "Veg",
            "vendor_type_id" => $foodVendorType->id ?? null,
        ]);
        Tag::firstOrCreate([
            "name" => "Non-Veg",
            "vendor_type_id" => $foodVendorType->id ?? null,
        ]);
    }
}
