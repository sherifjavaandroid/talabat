<?php

namespace App\Upgrades;

use App\Models\SmsGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade19 extends BaseUpgrade
{

    public $versionName = "1.3.4";
    //Runs or migrations to be done on this version
    public function run()
    {


        //force migration for new tables
        if (!Schema::hasTable('sms_gateways')) {
            Artisan::call('migrate --path=database/migrations/2021_08_18_133903_create_sms_gateways_table.php --force');
            Artisan::call('db:seed --class=SmsGatewaysTableSeeder --force');
        }
        if (!Schema::hasTable('auto_assignments')) {
            Artisan::call('migrate --path=database/migrations/2021_08_20_214110_create_auto_assignments_table.php --force');
        }
        if (!Schema::hasTable('otps')) {
            Artisan::call('migrate --path=database/migrations/2021_08_22_143802_create_otps_table.php --force');
        }
        //twilio sms gateway
        $smsGateway = SmsGateway::where('slug', "twilio")->first();
        if (empty($smsGateway)) {
            \DB::table('sms_gateways')->insert(array(
                0 =>
                array(
                    'name' => 'Twilio',
                    'slug' => 'twilio',
                    'is_active' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
            ));
        }


        //vendors auto accept order
        if (!Schema::hasColumn('vendors', 'auto_accept')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->boolean('auto_accept')->default(false)->after('auto_assignment');
            });
        }
        //
        if (!Schema::hasColumn('package_type_pricings', 'multiple_stop_fee')) {
            Schema::table('package_type_pricings', function (Blueprint $table) {
                $table->double('multiple_stop_fee', 8, 2)->default(0.00)->after('base_price');
            });
        }
        
        //aading status to auto assignments
        if (!Schema::hasColumn('auto_assignments', 'status')) {
            Schema::table('auto_assignments', function (Blueprint $table) {
                $table->enum('status', ['pending', 'rejected'])->default('pending')->after('driver_id');
            });
        }
        
        
    }


}
