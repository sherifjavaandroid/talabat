<?php

namespace App\Upgrades;

use App\Models\Payout;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade25 extends BaseUpgrade
{

    public $versionName = "1.4.1";
    //Runs or migrations to be done on this version
    public function run()
    {


        //
        if (!Schema::hasColumn('payouts', 'status')) {
            //
            \DB::statement("ALTER TABLE `payouts` CHANGE `payment_method_id` `payment_method_id` BIGINT(20) UNSIGNED NULL");
            \DB::statement("ALTER TABLE `payouts` CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NULL");

            Schema::table('payouts', function (Blueprint $table) {
                $table->foreignId('payment_account_id')->after('payment_method_id')->nullable()->constrained();
                $table->enum('status', ['review', 'failed', 'cancelled', 'successful'])->default('review')->after('note');
            });

            //set the status to old payouts to successful
            $payouts = Payout::get();
            foreach ($payouts as $payout) {
                $payout->status = "successful";
                $payout->save();
            }
        }

        //
        if (!Schema::hasColumn('subscriptions', 'qty')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->integer('qty')->nullable()->after('days');
            });
        }

        //
        if (!Schema::hasTable('push_notifications')) {
            Artisan::call('migrate --path=database/migrations/2021_10_24_033848_create_push_notifications_table.php --force');
        }

        //
        //
        if (!Schema::hasTable('payment_accounts')) {
            Artisan::call('migrate --path=database/migrations/2021_10_24_195435_create_payment_accounts_table.php --force');
        }
    }

   
}
