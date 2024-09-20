<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OnboardingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('onboardings')->delete();
        
        \DB::table('onboardings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'in_order' => 3,
                'title' => '{"en":"Struggling to choose a vendor for your needs?"}',
                'description' => '{"en":"Take advantage of our convenient platform and find what fits your needs without breaking a sweat! Enjoy browsing through different vendors from the comfort of your own home."}',
                'type' => 'customer',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:30:25',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            1 => 
            array (
                'id' => 2,
                'in_order' => 1,
                'title' => '{"en":"Need to get from A to B fast, cheap and reliable?"}',
                'description' => '{"en":"Look no further because our platform is the best way to go. Our rides are affordable, dependable and incredibly convenient. Tap away and let us take you where you need to be. #OrderARideToday"}',
                'type' => 'customer',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:30:52',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            2 => 
            array (
                'id' => 3,
                'in_order' => 2,
                'title' => '{"en":"Delivery made easy"}',
                'description' => '{"en":"Whether it\'s groceries or your online purchase – leave the hassle of arranging last-mile delivery to us. Enjoy lightning-fast, secure delivery with our platform because convenience should come quick!⁣"}',
                'type' => 'customer',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:31:11',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            3 => 
            array (
                'id' => 4,
                'in_order' => 1,
                'title' => '{"en":"Delivery made easy"}',
                'description' => '{"en":"Get notified as soon as an order is available for delivery"}',
                'type' => 'driver',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:35:14',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            4 => 
            array (
                'id' => 5,
                'in_order' => 1,
                'title' => '{"en":"Chat with vendor/customer"}',
            'description' => '{"en":"Call/Chat with vendor/customer for more info or update regarding assigned order(s)"}',
                'type' => 'driver',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:35:35',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            5 => 
            array (
                'id' => 6,
                'in_order' => 1,
                'title' => '{"en":"Earning"}',
                'description' => '{"en":"No more waiting for your paycheck - now you can earn on the go with our platform! Get ready to be your own boss and start racking up those commissions today."}',
                'type' => 'driver',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:35:58',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            6 => 
            array (
                'id' => 7,
                'in_order' => 1,
                'title' => '{"en":"Take Orders"}',
                'description' => '{"en":"Accept orders from customers"}',
                'type' => 'vendor',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:36:53',
                'updated_at' => '2024-05-01 13:17:32',
            ),
            7 => 
            array (
                'id' => 8,
                'in_order' => 1,
                'title' => '{"en":"Chat with driver/customer"}',
            'description' => '{"en":"Call/Chat with driver/customer for more info or update regarding assigned order(s)"}',
                'type' => 'vendor',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:37:12',
                'updated_at' => '2024-05-01 13:17:14',
            ),
            8 => 
            array (
                'id' => 9,
                'in_order' => 1,
                'title' => '{"en":"Earning"}',
                'description' => '{"en":"View earning from orders. Get a breakdown of earnings per day, total tax\\/commission charged and available payout amounts."}',
                'type' => 'vendor',
                'is_active' => 1,
                'created_at' => '2022-08-15 20:37:27',
                'updated_at' => '2024-05-01 13:18:17',
            ),
        ));
        
        
    }
}