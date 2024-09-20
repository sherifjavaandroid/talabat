<?php

namespace App\Upgrades;

use App\Models\Payout;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade27 extends BaseUpgrade
{

    public $versionName = "1.4.3";
    //Runs or migrations to be done on this version
    public function run()
    {
        //change wallet transactions
        \DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN `status` ENUM('pending', 'failed', 'successful','review') DEFAULT 'pending'  NOT NULL");

        if (!Schema::hasTable('taxi_currency_pricings')) {
            Artisan::call('migrate --path=database/migrations/2021_11_28_222351_create_taxi_currency_pricings_table.php --force');
        }

        if (!Schema::hasColumn('taxi_orders', 'currency_id')) {
            Schema::table('taxi_orders', function (Blueprint $table) {
                $table->foreignId('currency_id')->after('vehicle_type_id')->nullable()->constrained();
            });
        }
        
    }

}
