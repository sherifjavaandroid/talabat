<?php

namespace App\Upgrades;

use App\Models\SmsGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade21 extends BaseUpgrade
{

    public $versionName = "1.3.7";
    //Runs or migrations to be done on this version
    public function run()
    {

        //aading status to auto assignments
        if (!Schema::hasColumn('day_vendor', 'id')) {
            Schema::table('day_vendor', function (Blueprint $table) {
                $table->dropPrimary();
            });
            
            Schema::table('day_vendor', function (Blueprint $table) {
                $table->id()->first();
            });
        }

        if (!Schema::hasColumn('vendors', 'use_subscription')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->boolean('use_subscription')->default(false)->after('max_order');
            });
        }
        
        //termii sms gateway
        $smsGateway = SmsGateway::where('slug', "termii")->first();
        if (empty($smsGateway)) {
            \DB::table('sms_gateways')->insert(array(
                0 =>
                array(
                    'name' => 'Termii',
                    'slug' => 'termii',
                    'is_active' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
            ));
        }
        //africastalking sms gateway
        $smsGateway = SmsGateway::where('slug', "africastalking")->first();
        if (empty($smsGateway)) {
            \DB::table('sms_gateways')->insert(array(
                0 =>
                array(
                    'name' => 'AfricasTalking',
                    'slug' => 'africastalking',
                    'is_active' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
            ));
        }
        //hubtel sms gateway
        $smsGateway = SmsGateway::where('slug', "hubtel")->first();
        if (empty($smsGateway)) {
            \DB::table('sms_gateways')->insert(array(
                0 =>
                array(
                    'name' => 'Hubtel',
                    'slug' => 'hubtel',
                    'is_active' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
            ));
        }


        //
        if (!Schema::hasTable('subscriptions')) {
            Artisan::call('migrate --path=database/migrations/2021_09_10_190935_create_subscriptions_table.php --force');
        }
        if (!Schema::hasTable('subscription_vendor')) {
            Artisan::call('migrate --path=database/migrations/2021_09_10_230423_create_subscription_vendor_pivot_table.php --force');
        }
       
        
    }

   

}
