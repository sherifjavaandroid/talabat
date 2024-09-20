<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use GeoSot\EnvEditor\Facades\EnvEditor;

class Upgrade35 extends BaseUpgrade
{

    public $versionName = "1.5.2";
    //Runs or migrations to be done on this version
    public function run()
    {

        if (!Schema::hasTable('taxi_zones')) {
            Artisan::call('migrate --path=database/migrations/2022_03_15_092109_create_taxi_zones_table.php --force');
        }

        if (!Schema::hasTable('taxi_zone_points')) {
            Artisan::call('migrate --path=database/migrations/2022_03_15_092152_create_taxi_zone_points_table.php --force');
        }


        if (!Schema::hasTable('jobs')) {
            Artisan::call('migrate --path=database/migrations/2022_03_17_052722_create_jobs_table.php --force');
        }

        //set the 
        $this->createJobsTable();


    }


    public function createJobsTable()
    {

        $this->createOrUpdateEnv("QUEUE_DRIVER", "database");
        $this->createOrUpdateEnv("QUEUE_CONNECTION", "database");

        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function ($table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }
    }

    public function createOrUpdateEnv($key, $value)
    {
        $exists = EnvEditor::keyExists($key);
        if ($exists) {
            $queueDriver = EnvEditor::getKey($key, $default = null);
            if ($queueDriver == null || $queueDriver != $value) {
                EnvEditor::editKey($key, $value);
            }
        } else {
            EnvEditor::addKey($key, $value);
        }
    }

   
}
