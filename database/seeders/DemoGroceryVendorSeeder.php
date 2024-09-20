<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Traits\ImageGeneratorTrait;
use Illuminate\Database\Seeder;

class DemoGroceryVendorSeeder extends Seeder
{

    use ImageGeneratorTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $vendorTypeId = VendorType::where('slug', 'grocery')->first()->id;
        //delete all vendors with food type
        // $vendorIds = Vendor::where('vendor_type_id', $vendorTypeId)->pluck('id')->toArray();
        // Product::whereIn('vendor_id', $vendorIds)->delete();
        // Vendor::where('vendor_type_id', $vendorTypeId)->delete();


        //
        //create 2 popular grocery vendors/companies: Walmart, Target
        $vendorNames = ['Walmart', 'Target'];
        //short descriptions
        $vendorDecriptions = [
            //Walmart
            "Walmart Inc. is an American multinational retail corporation that operates a chain of hypermarkets, discount department stores, and grocery stores from the United States, headquartered in Bentonville, Arkansas. The company was founded by Sam Walton in 1962 and incorporated on October 31, 1969.",
            //Target
            "Target Corporation is an American retail corporation. It is the 8th-largest retailer in the United States, and is a component of the S&P 500 Index. It is unrelated to Target Australia."
        ];

        //array of best selling products from each vendor, name: description at least 4 products
        $vendorProducts = [
            // walmart - milk, carrot, apple, eggs, bread
            [
                [
                    'name' => 'Milk',
                    'description' => 'Milk is a nutrient-rich liquid food produced by the mammary glands of mammals. It is the primary source of nutrition for young mammals, including breastfed human infants before they are able to digest solid food.',
                ],
                [
                    'name' => 'Carrot',
                    'description' => 'The carrot is a root vegetable, usually orange in color, though purple, black, red, white, and yellow cultivars exist. They are a domesticated form of the wild carrot, Daucus carota, native to Europe and Southwestern Asia.',
                ],
                [
                    'name' => 'Apple',
                    'description' => 'An apple is an edible fruit produced by an apple tree. Apple trees are cultivated worldwide and are the most widely grown species in the genus Malus. The tree originated in Central Asia, where its wild ancestor, Malus sieversii, is still found today.',
                ],
                [
                    'name' => 'Eggs',
                    'description' => "Fresh Eggs",
                ],
                [
                    'name' => 'Bread',
                    'description' => "Fresh Bread straight from the oven. No preservatives",
                ]
            ],
            // target
            [
                //products: fish, chicken wings, beef, pork, lamb
                //the descriptions should be selling points
                [
                    'name' => 'Fish',
                    'description' => 'Fish are gill-bearing aquatic craniate animals that lack limbs with digits. They form a sister group to the tunicates, together forming the olfactores. Included in this definition are the living hagfish, lampreys, and cartilaginous and bony fish as well as various extinct related groups.',
                ],
                [
                    'name' => 'Chicken Wings',
                    'description' => 'Chicken wings are a dish from the cuisine of the United States. After deep-frying, the wings are usually shaken in a sauce consisting of a vinegar-based cayenne pepper hot sauce and melted butter prior to serving.',
                ],
                [
                    'name' => 'Beef',
                    'description' => 'Beef is the culinary name for meat from cattle, particularly skeletal muscle. Humans have been eating beef since prehistoric times. Beef is a source of high-quality protein and nutrients.',
                ],
                [
                    'name' => 'Pork',
                    'description' => "Pork is the culinary name for the meat of a domestic pig. It is the most commonly consumed meat worldwide, with evidence of pig husbandry dating back to 5000 BC.",
                ],
                [
                    'name' => 'Lamb',
                    'description' => "Lamb, hogget, and mutton, generically sheep meat, are the meat of domestic sheep, Ovis aries. A sheep in its first year is a lamb and its meat is also lamb. A sheep in its second year and its meat are hogget. Older sheep meat is mutton.",
                ],
            ],
        ];
        //
        $faker = \Faker\Factory::create();
        //Loop through the vendor names
        foreach ($vendorNames as $key => $vendorName) {
            $model = new Vendor();
            $model->name = $vendorName;
            $model->description = $vendorDecriptions[$key];
            $model->delivery_fee = rand(5, 100);
            $model->delivery_range = rand(10, 6000);
            $model->phone = $faker->phoneNumber;
            $model->email = $faker->email;
            $model->address = $faker->address;
            $model->latitude = $faker->latitude();
            $model->longitude = $faker->longitude();
            $model->tax = rand(0, 13);
            $model->pickup = rand(0, 1);
            $model->delivery = rand(0, 1);
            $model->is_active = 1;
            $model->vendor_type_id = $vendorTypeId;
            $model->saveQuietly();
            //
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
                $product->deliverable = rand(0, 1);
                $product->featured = rand(0, 1);
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