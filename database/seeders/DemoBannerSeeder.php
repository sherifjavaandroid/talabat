<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class DemoBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create categories by vendor type and assign to random products and services
        $banner = new Banner();
        $banner->link = "https://codecanyon.net/item/fuodz-grocery-food-pharmacy-store-parcelcourier-delivery-mobile-app-with-php-laravel-backend/31145802";
        $banner->featured = true;
        $banner->save();

        $imageUrl = "https://codecanyon.img.customer.envatousercontent.com/files/483888557/Glover%20fresh%20preview%20-%202024.png?auto=compress%2Cformat&q=80&fit=crop&crop=top&max-h=8000&max-w=590&s=ca57e1ff370ac40d0efff087958062ce";
        $banner->addMediaFromUrl($imageUrl)->toMediaCollection();
    }
}
