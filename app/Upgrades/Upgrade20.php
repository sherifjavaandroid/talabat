<?php

namespace App\Upgrades;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Upgrade20 extends BaseUpgrade
{

    public $versionName = "1.3.5";
    //Runs or migrations to be done on this version
    public function run()
    {

        //aading status to auto assignments
        if (!Schema::hasColumn('auto_assignments', 'status')) {
            Schema::table('auto_assignments', function (Blueprint $table) {
                $table->enum('status', ['pending', 'rejected'])->default('pending')->after('driver_id');
            });
        }
        
        
    }


}
