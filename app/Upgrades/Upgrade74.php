<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\DB;

class Upgrade74 extends BaseUpgrade
{

    public $versionName = "1.7.50";
    //Runs or migrations to be done on this version
    public function run()
    {


        //add boundaries to countries, states and cities tables
        try {
            DB::beginTransaction();
            $tables = ['countries', 'states', 'cities'];
            foreach ($tables as $table) {

                //if table has status column, drop the column
                if (\Schema::hasColumn($table, 'status')) {
                    \Schema::table($table, function ($table) {
                        $table->dropColumn('status');
                    });
                }

                // //add is_active column to the table if it doesn't exist
                // if (!\Schema::hasColumn($table, 'is_active')) {
                //     \Schema::table($table, function ($table) {
                //         $table->boolean('is_active')->default(1)->after('name');
                //     });
                // }

                //add the boundaries column to the table if it doesn't exist
                if (!\Schema::hasColumn($table, 'boundaries')) {
                    \Schema::table($table, function ($table) {
                        $table->longText('boundaries')->nullable()->after('name');
                    });
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        //add vendor_type_id to banners table
        try {
            DB::beginTransaction();
            if (!\Schema::hasColumn('banners', 'vendor_type_id')) {
                \Schema::table('banners', function ($table) {
                    $table->foreignId('vendor_type_id')->nullable()->constrained()->after('vendor_id');
                });
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}