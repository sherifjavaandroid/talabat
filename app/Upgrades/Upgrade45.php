<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\VendorManager;

class Upgrade45 extends BaseUpgrade
{

    public $versionName = "1.6.1";
    //Runs or migrations to be done on this version
    public function run()
    {

        //migrate earning_reports
        if (!Schema::hasTable("earning_reports")) {
            Artisan::call('migrate --path=database/migrations/2022_10_06_004047_create_earning_reports_table.php --force');
        }

        //admin_commission
        if (!Schema::hasColumn("commissions", 'admin_commission')) {
            Schema::table("commissions", function ($table) {
                $table->double('admin_commission', 15, 8)->default(0.00)->after('order_id');
            });
        }
        //
        if (!Schema::hasColumn("coupons", 'vendor_type_id')) {
            Schema::table("coupons", function ($table) {
                $table->foreignId('vendor_type_id')->nullable()->constrained()->after('creator_id');
            });
        }

        //migrate vendor_managers
        if (!Schema::hasTable("vendor_managers")) {
            
            Artisan::call('migrate --path=database/migrations/2022_10_25_132739_create_vendor_managers_table.php --force');
            //add the managers and vendor in the models
            $managers = User::whereHas('roles', function ($query) {
                $query->where('name', "manager");
            })
                ->whereNotNull('vendor_id')
                ->select('id', 'vendor_id')->get();
            //
            foreach ($managers as $manager) {
                //
                VendorManager::firstOrCreate([
                    "user_id" => $manager->id,
                    "vendor_id" => $manager->vendor_id,
                ]);
            }
        
        }
    }
}
