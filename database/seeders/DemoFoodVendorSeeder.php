<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Traits\ImageGeneratorTrait;
use Illuminate\Database\Seeder;

class DemoFoodVendorSeeder extends Seeder
{

    use ImageGeneratorTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $foodVendorTypeId = VendorType::where('slug', 'food')->first()->id;
        $vendorIds = Vendor::where('vendor_type_id', $foodVendorTypeId)->pluck('id')->toArray();
        Product::whereIn('vendor_id', $vendorIds)->delete();
        Vendor::where('vendor_type_id', $foodVendorTypeId)->delete();


        //
        //create 4 popular food vendors: KFC, McDonalds, Burger King, Subway
        $vendorNames = ['KFC', 'McDonalds', 'Burger King', 'Subway'];
        //short descriptions
        $vendorDecriptions = [
            "KFC is an American fast food restaurant chain headquartered in Louisville, Kentucky, that specializes in fried chicken.",
            //McDonald
            "McDonald's Corporation is an American fast food company, founded in 1940 as a restaurant operated by Richard and Maurice McDonald, in San Bernardino, California, United States.",
            //Burger
            "Burger King (BK) is an American multinational chain of hamburger fast food restaurants.",
            //Subway
            "Subway is an American privately held restaurant franchise that primarily sells submarine sandwiches (subs) and salads."
        ];
        //array of best sending food from each vendor
        $vendorFoods = [
            ['Fried Chicken', 'Chicken Burger', 'Chicken Wings', 'Chicken Nuggets'],
            ['Big Mac', 'Cheeseburger', 'McChicken', 'Filet-O-Fish'],
            ['Whopper', 'Bacon King', 'Double Whopper', 'Bacon Double Cheeseburger'],
            ['Italian B.M.T.', 'Meatball Marinara', 'Spicy Italian', 'Chicken & Bacon Ranch Melt']
        ];
        //
        $faker = \Faker\Factory::create();
        //Loop through the vendor names
        foreach ($vendorNames as $key => $vendorName) {
            $model = new Vendor();
            $model->name = $vendorName;
            $model->description = $vendorDecriptions[$key];
            $model->delivery_fee = $faker->randomNumber(2, false);
            $model->delivery_range = $faker->randomNumber(3, false);
            $model->tax = $faker->randomNumber(2, false);
            $model->phone = $faker->phoneNumber;
            $model->email = $faker->email;
            $model->address = $faker->address;
            $model->latitude = $faker->latitude();
            $model->longitude = $faker->longitude();
            $model->tax = rand(0, 1);
            $model->pickup = rand(0, 1);
            $model->delivery = rand(0, 1);
            $model->is_active = 1;
            $model->vendor_type_id = $foodVendorTypeId;
            $model->saveQuietly();
            //logo image
            try {
                $imageUrl = $this->generateImage($vendorName, "business,logo");
                $model->clearMediaCollection();
                $model->addMediaFromUrl($imageUrl)
                    ->usingFileName(genFileName($imageUrl))
                    ->toMediaCollection("logo");
                $featureImageUrl = $this->generateImage($vendorName, "banner,advert", "landscape");
                $model->addMediaFromUrl($featureImageUrl)
                    ->usingFileName(genFileName($featureImageUrl))
                    ->toMediaCollection("feature_image");
            } catch (\Exception $ex) {
                logger("Error", [$ex->getMessage()]);
            }
            //add food
            $vendorFoodList = $vendorFoods[$key];
            foreach ($vendorFoodList as $foodName) {
                $food = new Product();
                $food->name = $foodName;
                $food->description = $faker->catchPhrase;
                $food->price = rand(5, 1000);
                $food->is_active = 1;
                $food->deliverable = rand(0, 1);
                $food->featured = rand(0, 1);
                $food->vendor_id = $model->id;
                $food->saveQuietly();
                //
                try {
                    $imageUrl = $this->generateImage($foodName, "food");
                    $food->addMediaFromUrl($imageUrl)
                        ->usingFileName(genFileName($imageUrl))
                        ->toMediaCollection();
                } catch (\Exception $ex) {
                    logger("Error", [$ex->getMessage()]);
                }
            }
        }
    }
}