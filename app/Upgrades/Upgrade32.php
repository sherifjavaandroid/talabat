<?php

namespace App\Upgrades;

use App\Models\User;
use App\Models\VendorType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade32 extends BaseUpgrade
{

    public $versionName = "1.4.8";
    //Runs or migrations to be done on this version
    public function run()
    {

        //adding in_order to some tables
        $tables = ['banners', 'vendor_types', 'menus', 'categories', 'subcategories', 'option_groups', 'options', 'package_types', 'products', 'vendors', 'vehicle_types'];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'in_order')) {
                Schema::table($table, function ($table) {
                    $table->integer('in_order')->default(1);
                });
            }
        }

        //add commerce vendor type 
        $vendorType = VendorType::whereSlug('commerce')->first();
        if (empty($vendorType)) {
            $vendorType = new VendorType();
            $vendorType->slug = "commerce";
            $vendorType->name = "E-commerce";
            $vendorType->description = "E-commerce activities";
            $vendorType->color = "#24A19C";
            $vendorType->save();
        }

        //add prepare time to vendor
        if (!Schema::hasColumn("vendors", 'prepare_time')) {
            Schema::table("vendors", function ($table) {
                $table->string('prepare_time')->nullable();
                $table->string('delivery_time')->nullable();
            });
        }
    }
}
