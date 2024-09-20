<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Artisan;

class Upgrade59 extends BaseUpgrade
{

    public $versionName = "1.7.00";
    //Runs or migrations to be done on this version
    public function run()
    {
        //comment out the line below to run the upgrade
        $columnExists = \Schema::hasColumn('personal_access_tokens', 'expires_at');
        if (!$columnExists) {
            \Schema::table('personal_access_tokens', function ($table) {
                $table->timestamp('expires_at')->nullable()->after('last_used_at');
            });
        }

        //run a migration
        $columnExists = \Schema::hasColumn('media', 'generated_conversions');
        if (!$columnExists) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/2023_08_06_021331_add_generated_conversions_to_media_table.php",
                '--force' => true,
            ]);
        }

        //run a migration for vendor settings
        $tableExists = \Schema::hasTable('vendor_settings');
        if (!$tableExists) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/2023_08_10_204655_create_vendor_settings_table.php",
                '--force' => true,
            ]);
        }

        //run a migration for cancellation reasons
        $tableExists = \Schema::hasTable('cancellation_reasons');
        if (!$tableExists) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/2023_08_14_025457_create_cancellation_reasons_table.php",
                '--force' => true,
            ]);

            //add the default reasons
            $reasons = [
                [
                    'type' => "both",
                    'reason' => "I don't need it anymore",
                ],
                [
                    'type' => "both",
                    'reason' => "I ordered by mistake",
                ],
                [
                    'type' => "both",
                    'reason' => "I can't contact the driver",
                ]
            ];

            //
            foreach ($reasons as $reason) {
                $cancelReason = new \App\Models\CancellationReason();
                $cancelReason->type = $reason['type'];
                $cancelReason->reason = $reason['reason'];
                $cancelReason->save();
            }
        }


        //set driversearchradius to 50 if it is not set
        $driverSearchRadius = (float) setting('driverSearchRadius', 10);
        //if value is more than earth radius, set it to earth radius in km
        if ($driverSearchRadius > 6371) {
            $driverSearchRadius = 6371;
            setting(['driverSearchRadius' => $driverSearchRadius])->save();
        }


        //run a migration for document requests
        $tableExists = \Schema::hasTable('document_requests');
        if (!$tableExists) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/2023_08_14_141820_create_document_requests_table.php",
                '--force' => true,
            ]);
        }
    }
}
