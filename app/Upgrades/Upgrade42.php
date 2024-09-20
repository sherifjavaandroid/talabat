<?php

namespace App\Upgrades;


use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use App\Services\JobHandlerService;

class Upgrade42 extends BaseUpgrade
{

    public $versionName = "1.5.9";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasColumn("services", 'location')) {
            Schema::table("services", function ($table) {
                $table->boolean('location')->default(true)->after('is_active');
            });
        }

        //
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table("vendors", function ($table) {
            $table->double('base_delivery_fee', 15, 2)->nullable()->change();
            $table->double('delivery_fee', 15, 2)->nullable()->change();
            $table->double('delivery_range', 8, 2)->nullable()->change();
            $table->boolean('charge_per_km')->nullable()->change();
        });


       

    }
}
