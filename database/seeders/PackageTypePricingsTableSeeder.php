<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageTypePricingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('package_type_pricings')->delete();
    
        
    }
}