<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FeesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fees')->delete();
        
        \DB::table('fees')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'Packaging Fee',
                'value' => 5.0,
                'percentage' => 0,
                'is_active' => 1,
                'created_at' => '2022-08-22 07:42:10',
                'updated_at' => '2022-08-22 07:42:10',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}