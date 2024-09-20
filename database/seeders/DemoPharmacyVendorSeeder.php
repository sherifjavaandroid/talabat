<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Traits\ImageGeneratorTrait;
use Illuminate\Database\Seeder;

class DemoPharmacyVendorSeeder extends Seeder
{
    use ImageGeneratorTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $vendorTypeId = VendorType::where('slug', 'pharmacy')->first()->id;
        // $vendorIds = Vendor::where('vendor_type_id', $vendorTypeId)->pluck('id')->toArray();
        // Product::whereIn('vendor_id', $vendorIds)->delete();
        // Vendor::where('vendor_type_id', $vendorTypeId)->delete();

        //
        //create 1 popular pharmacy vendors/companies: CVS
        $vendorNames = ['CVS'];
        //short descriptions
        $vendorDecriptions = [
            //CVS
            "CVS Pharmacy is an American retail corporation. Owned by CVS Health, it is headquartered in Woonsocket, Rhode Island. It was also known as, and originally named, the Consumer Value Store and was founded in Lowell, Massachusetts, in 1963.",
        ];

        //array of best selling products from each vendor, name: description at least 4 products
        $vendorProducts = [
            // CVS - mouthwash, toothbrush, condom, vitamin c
            [
                [
                    "name" => "Mouthwash",
                    "description" => "Mouthwash, mouth rinse, oral rinse, or mouth bath is a liquid which is held in the mouth passively or swilled around the mouth by contraction of the perioral muscles and/or movement of the head, and may be gargled, where the head is tilted back and the liquid bubbled at the back of the mouth.",
                ],
                [
                    "name" => "Colgate Toothbrush",
                    "description" => "Colgate is an American brand principally used for oral hygiene products such as toothpastes, toothbrushes, mouthwashes and dental floss. Manufactured by Colgate-Palmolive, Colgate oral hygiene products were first sold by the company in 1873, sixteen years after the death of the founder, William Colgate.",
                ],
                [
                    "name" => "Durex Condom",
                    "description" => "Durex is a British brand of condoms and sexual health products owned by Reckitt Benckiser. They were established in 1915 as Durex: The London Rubber Company. In 1929, their first latex condom was launched. In 1950, the company was granted a Royal Warrant by Queen Elizabeth II.",
                ],
                [
                    "name" => "Vitamin C",
                    "description" => "Vitamin C, also known as ascorbic acid and ascorbate, is a vitamin found in various foods and sold as a dietary supplement. It is used to prevent and treat scurvy. Vitamin C is an essential nutrient involved in the repair of tissue and the enzymatic production of certain neurotransmitters.",
                ]
            ],
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
            $model->pickup = 1;
            $model->delivery = 0;
            $model->is_active = 1;
            $model->vendor_type_id = $vendorTypeId;
            $model->saveQuietly();
            //logo gen
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

            //add product
            $vendorProductList = $vendorProducts[$key];
            foreach ($vendorProductList as $vendorProductData) {
                $product = new Product();
                $product->name = $vendorProductData['name'];
                $product->description = $vendorProductData['description'];
                $product->price = $faker->randomNumber(4, false);
                $product->is_active = 1;
                $product->deliverable = 0;
                $product->featured = 0;
                $product->vendor_id = $model->id;
                $product->saveQuietly();
                //
                try {
                    $imageUrl = $this->generateImage($product->name, "Grocery");
                    $product->addMediaFromUrl($imageUrl)
                        ->usingFileName(genFileName($imageUrl))
                        ->toMediaCollection();
                } catch (\Exception $ex) {
                    logger("Error", [$ex->getMessage()]);
                }
            }
        }
    }
}