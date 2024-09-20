<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;

class Upgrade62 extends BaseUpgrade
{

    public $versionName = "1.7.10";
    //Runs or migrations to be done on this version
    public function run()
    {
        //add in_order column to services table
        if (!Schema::hasColumn('services', 'in_order')) {
            Schema::table('services', function ($table) {
                $table->integer('in_order')->default(1);
            });
        }

        //add max_options column to service_option_groups
        if (!Schema::hasColumn('service_option_groups', 'max_options')) {
            Schema::table('service_option_groups', function ($table) {
                $table->integer('max_options')->nullable();
            });
        }
        //add max_options column to option_groups table
        if (!Schema::hasColumn('option_groups', 'max_options')) {
            Schema::table('option_groups', function ($table) {
                $table->integer('max_options')->nullable();
            });
        }
        //add amount breakdown to taxi_orders table
        $columns = ['base_fare', 'distance_fare', 'time_fare', 'trip_distance', 'trip_time'];
        foreach ($columns as $column) {
            if (!Schema::hasColumn('taxi_orders', $column)) {
                Schema::table('taxi_orders', function ($table) use ($column) {
                    $table->string($column)->nullable();
                });
            }
        }

        //add age_restricted to products, services table
        $tables = ['products', 'services'];
        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'age_restricted')) {
                Schema::table($table, function ($table) {
                    $table->boolean('age_restricted')->default(false);
                });
            }
        }


        //add for_delivery to coupons table
        if (!Schema::hasColumn('coupons', 'for_delivery')) {
            Schema::table('coupons', function ($table) {
                $table->boolean('for_delivery')->default(false);
            });
        }


        //migrate content_pages to database if not already migrated
        $filePath = 'database/migrations/2023_10_02_071828_create_content_pages_table.php';
        $this->runMigration($filePath, 'content_pages');

        //
    }
}
