<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('package_types')->delete();


    }
}
