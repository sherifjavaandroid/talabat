<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SmsGatewaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sms_gateways')->delete();
        
        \DB::table('sms_gateways')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Twilio',
                'slug' => 'twilio',
                'is_active' => 0,
                'created_at' => '2021-08-20 14:25:02',
                'updated_at' => '2021-08-20 14:25:02',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'MSG91',
                'slug' => 'msg91',
                'is_active' => 1,
                'created_at' => '2021-08-20 14:25:02',
                'updated_at' => '2021-08-24 22:36:15',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'GatewayAPI',
                'slug' => 'gatewayapi',
                'is_active' => 1,
                'created_at' => '2021-08-20 14:25:02',
                'updated_at' => '2021-08-24 22:36:15',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Termii',
                'slug' => 'termii',
                'is_active' => 0,
                'created_at' => '2022-03-23 21:37:55',
                'updated_at' => '2022-03-23 21:37:55',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'AfricasTalking',
                'slug' => 'africastalking',
                'is_active' => 0,
                'created_at' => '2022-03-23 21:37:55',
                'updated_at' => '2022-03-23 21:37:55',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Hubtel',
                'slug' => 'hubtel',
                'is_active' => 0,
                'created_at' => '2022-03-23 21:37:55',
                'updated_at' => '2022-03-23 21:37:55',
            ),
        ));
        
        
    }
}