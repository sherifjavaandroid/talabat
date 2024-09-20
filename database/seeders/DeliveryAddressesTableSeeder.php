<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeliveryAddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('delivery_addresses')->delete();

        
    }
}