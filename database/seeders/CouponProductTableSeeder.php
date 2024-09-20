<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CouponProductTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('coupon_product')->delete();    
        
    }
}