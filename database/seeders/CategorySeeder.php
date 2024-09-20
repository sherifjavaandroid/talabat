<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\VendorType;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //truncate tables
        //disable foreign key check for this operation
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        Subcategory::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //service categories
        $serviceVendorType = VendorType::whereSlug('service')->first();
        $names = [
            [
                "name" => "Home",
                "subs" => [
                    "Cleaning",
                    "Handyman",
                    "Landscaping",
                    "Moving",
                    "Painting",
                    "Plumbing",
                    "Remodeling",
                    "Roofing",
                    "Electrical",
                ],
            ],
            [
                "name" => "Health",
                "subs" => [
                    "Dentist",
                    "Doctor",
                    "Optometrist",
                    "Pharmacy",
                    "Physical Therapy",
                    "Psychologist",
                    "Veterinarian",
                ],
            ],
            [
                "name" => "Beauty",
                "subs" => [
                    "Barber",
                    "Hair Salon",
                    "Makeup Artist",
                    "Nail Salon",
                    "Spa",
                    "Tattoo Artist",
                ],
            ],
            [
                "name" => "Auto",
                "subs" => [
                    "Auto Body",
                    "Auto Detailing",
                    "Auto Glass",
                    "Auto Repair",
                    "Car Wash",
                    "Motorcycle Repair",
                    "Oil Change",
                    "Tire Shop",
                ],
            ],
            [
                "name" => "Education",
                "subs" => [
                    "Driving School",
                    "Music Lessons",
                    "Tutoring",
                ],
            ],
            [
                "name" => "Events",
                "subs" => [
                    "Catering",
                    "DJ",
                    "Event Planner",
                    "Photographer",
                    "Wedding Planner",
                ],
            ],
            [
                "name" => "Fitness",
                "subs" => [
                    "Gym",
                    "Personal Trainer",
                    "Yoga",
                ],
            ],
            [
                "name" => "Legal",
                "subs" => [
                    "Bankruptcy Attorney",
                    "Criminal Defense Attorney",
                    "Divorce Attorney",
                    "Estate Planning Attorney",
                    "Family Law Attorney",
                ],
            ],
        ];
        $imagepath = "images/categories/service/";
        $this->syncCategories($names, $serviceVendorType, $imagepath);


        //food vendor type
    }


    private function syncCategories($names, $vendorType, $imagepath)
    {
        $savedColor = setting('websiteColor', '#21a179');
        $appColor = new \OzdemirBurak\Iris\Color\Hex($savedColor);
        $primaryColorFaint = $appColor->brighten(70);
        foreach ($names as $key => $name) {

            $category = new Category();
            $category->name = $name;
            $category->color =  $primaryColorFaint ?? "#000000";
            $category->is_active = true;
            $category->vendor_type_id = $vendorType->id;
            $category->save();

            //add media
            try {
                $imgIndex = $key + 1;
                $mediaFilePath = $imagepath . "$imgIndex.png";
                $category->clearMediaCollection();
                $category->addMedia(public_path($mediaFilePath))->preservingOriginal()->toMediaCollection();
            } catch (\Exception $e) {
                //do nothing
                logger("Category image not found: $mediaFilePath", [$e]);
            }

            foreach ($name["subs"] as $sub) {
                Subcategory::create([
                    "name" => $sub,
                    "category_id" => $category->id,
                    'is_active' => 1,
                ]);
            }
        }
    }
}