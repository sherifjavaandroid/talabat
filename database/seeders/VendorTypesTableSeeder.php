<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendorTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('vendor_types')->delete();

        \DB::table('vendor_types')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => '{"en":"Parcel Delivery"}',
                'color' => '#8c28f0',
                'description' => '{"en":"Send parcel to people"}',
                'slug' => 'parcel',
                'is_active' => 1,
                'in_order' => 7,
                'created_at' => '2021-06-30 10:45:53',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => '{"en":"Food Delivery "}',
                'color' => '#97b500',
                'description' => '{"en":"Buy the best meal from your nearby restaurant"}',
                'slug' => 'food',
                'is_active' => 1,
                'in_order' => 2,
                'created_at' => '2021-06-30 10:45:53',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => '{"en":"Grocery"}',
                'color' => '#45b12f',
                'description' => '{"en":"Buy grocery from your nearby markets"}',
                'slug' => 'grocery',
                'is_active' => 1,
                'in_order' => 3,
                'created_at' => '2021-06-30 13:59:15',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => '{"en":"Pharmacy"}',
                'color' => '#79dde1',
                'description' => '{"en":"Buy drugs for your sickness and get it delivered directly to your doorstep"}',
                'slug' => 'pharmacy',
                'is_active' => 1,
                'in_order' => 4,
                'created_at' => '2021-06-30 14:01:27',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => '{"en":"Services"}',
                'color' => '#00bfff',
                'description' => '{"en":"Get services from your nearby service providers"}',
                'slug' => 'service',
                'is_active' => 1,
                'in_order' => 6,
                'created_at' => '2021-07-15 00:38:10',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => '{"en":"Taxi Booking"}',
                'color' => '#ffc036',
                'description' => '{"en":"Book Ride to your destination"}',
                'slug' => 'taxi',
                'is_active' => 1,
                'in_order' => 8,
                'created_at' => '2021-07-15 00:38:10',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => '{"en":"Booking"}',
                'color' => '#6e0000',
                'description' => '{"en":"Hotel/Housing/Rental Booking"}',
                'slug' => 'service',
                'is_active' => 1,
                'in_order' => 5,
                'created_at' => '2022-01-14 14:44:52',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => '{"en":"E-commerce"}',
                'color' => '#24A19C',
                'description' => '{"en":"Shopping from your Favourite Outlet "}',
                'slug' => 'commerce',
                'is_active' => 1,
                'in_order' => 1,
                'created_at' => '2022-02-09 22:46:30',
                'updated_at' => '2024-05-01 10:50:34',
                'deleted_at' => NULL,
            ),
        ));
    }
}
