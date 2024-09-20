<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;

class Upgrade68 extends BaseUpgrade
{

    public $versionName = "1.7.41";
    //Runs or migrations to be done on this version
    public function run()
    {
        //device_uuid column to user_tokens table, if not exists
        if (!Schema::hasColumn("user_tokens", 'device_uuid')) {
            Schema::table('user_tokens', function ($table) {
                $table->string('device_uuid')->nullable();
            });
        }

        //add min_order & max_order to payment_methods table
        if (!Schema::hasColumn("payment_methods", 'min_order')) {
            Schema::table('payment_methods', function ($table) {
                $table->decimal('min_order', 15, 4)->nullable();
                $table->decimal('max_order', 15, 4)->nullable();
            });
        }

        //add is_mobile column to user_tokens table
        if (!Schema::hasColumn("user_tokens", 'is_mobile')) {
            Schema::table('user_tokens', function ($table) {
                $table->boolean('is_mobile')->default(true);
            });
        }
    }
}
