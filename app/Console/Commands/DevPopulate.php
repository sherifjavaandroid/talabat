<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use App\Models\VendorType;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DevPopulate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev populate vendor and product data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $confirmText = __('Do you wish to continue?');
        if (!\App::environment('production')) {
            $confirmText = __('In Production, do you wish to continue?');
        }


        if (!$this->confirm($confirmText, false)) {
            $this->error('Operation cancelled');
            return 0;
        }

        //generate data to test 
        //
        $useSlugs = ["food", "grocery", "commerce", "pharmacy"];
        $vendorTypeProductNames = [
            ["egg", 'rice', "burger", "chips", "chicken", "pizza", "cake"],
            ["milk", 'soap', 'bottle water', "corn", "egg", "honey", "meat", "fish"],
            ["T-shirt", 'School bag', 'Ceiling Fan', "air cooler", "baby wear", "fashion", "tech", "gadgets"],
            ["painkiller", 'drug', 'first aid', "inslin"],
        ];

        $foundVendorTypesId = VendorType::whereIn('slug', $useSlugs)->pluck('id');
        if ($this->confirm("Delete existing vendors?", false)) {
            Vendor::whereIn('vendor_type_id', $foundVendorTypesId)->delete();
            $this->info('Vendors Deleted');
        }
        if ($this->confirm("Delete existing products?", false)) {
            \Schema::disableForeignKeyConstraints();
            Product::truncate();
            \Schema::enableForeignKeyConstraints();
            $this->info('Products Deleted');
        }

        foreach ($useSlugs as $index => $slug) {
            $this->info("Stating Slug:: {$slug}");
            $foundVendorType = VendorType::where('slug', $slug)->first();
            $vendors = Vendor::where('vendor_type_id', $foundVendorType->id)->get();
            $numberOfVendors = rand(2, 5);
            if (!empty($vendors) && count($vendors) > 0) {
                $numberOfVendors = $numberOfVendors - count($vendors);
                if ($numberOfVendors > 0) {
                    $vendors = null;
                }
            }
            // foreach ($vendors as $vendor) {
            //     $vendor->delete();
            // }

            if (empty($vendors) || count($vendors) == 0) {
                logger("empty vendors");
                $this->info("Empty vendors");
                $this->info("Creating vendors");
                $faker = \Faker\Factory::create();
                for ($i = 0; $i < $numberOfVendors; $i++) {
                    logger("new vendor", [$i]);
                    $vendor = new Vendor();
                    $vendor->name = $faker->company;
                    $vendor->description = $faker->catchPhrase;
                    $vendor->delivery_fee = $faker->randomNumber(2, false);
                    $vendor->delivery_range = $faker->randomNumber(3, false);
                    $vendor->tax = $faker->randomNumber(2, false);
                    $vendor->phone = $faker->phoneNumber;
                    $vendor->email = $faker->email;
                    $vendor->address = $faker->address;
                    $vendor->latitude = $faker->latitude();
                    $vendor->longitude = $faker->longitude();
                    $vendor->tax = rand(0, 1);
                    $vendor->pickup = rand(0, 1);
                    $vendor->delivery = rand(0, 1);
                    $vendor->is_active = 1;
                    $vendor->vendor_type_id = $foundVendorType->id;
                    $vendor->save();
                    $this->info("Vendor:: " . ($i + 1) . "/{$numberOfVendors}");

                    //
                    try {
                        $vendor->addMediaFromUrl("https://source.unsplash.com/240x240/?logo")->toMediaCollection("logo");
                        $vendor->addMediaFromUrl("https://source.unsplash.com/420x240/?vendor")->toMediaCollection("feature_image");
                    } catch (\Exception $ex) {
                        logger("Error Vendor", [$ex->getMessage()]);
                    }
                }
                $vendors = Vendor::where('vendor_type_id', $foundVendorType->id)->get();
            }


            //create new vendors
            $this->info("Products for Slug Vendors:: {$slug}");
            foreach ($vendors as $vendor) {
                $this->info("Products for:: {$vendor->name}");
                //products
                $productNames = $vendorTypeProductNames[$index];
                $mNames = json_encode($productNames);
                $this->info("ProductNames:: {$mNames}");

                $keyword = $productNames[rand(0, count($productNames) - 1)];
                $this->info("Selected Keyword:: {$keyword}");
                sleep(2);
                $productsArray = $this->getProducts($keyword);

                //
                foreach ($productsArray as $productObject) {

                    if ($productObject["price"] == null) {
                        $productObject["price"] = rand(1, 1000);
                    }
                    $product = new Product();
                    $product->name = $productObject["name"];
                    $product->description = $productObject["name"];
                    $product->price = $productObject["price"];
                    $product->discount_price = rand(0, $product->price);
                    $product->capacity = "";
                    $product->unit = "";
                    $product->package_count = 1;
                    $product->featured = rand(0, 1);
                    $product->deliverable = rand(0, 1);
                    $product->is_active = 1;
                    $product->vendor_id = $vendor->id;
                    $product->save();

                    //
                    try {
                        $pImage = $productObject["image"];
                        if (!filter_var($pImage, FILTER_VALIDATE_URL)) {
                            $pImage = "http:{$pImage}";
                        }
                        $product->addMediaFromUrl($pImage)->toMediaCollection();
                    } catch (\Exception $ex) {
                        logger("Error Product", [$ex->getMessage()]);
                        try {
                            $product->addMediaFromUrl("https://source.unsplash.com/420x240/?vendor,{$product->name}")
                                ->toMediaCollection();
                        } catch (\Exception $ex) {
                            logger("unsplash Error", [$ex->getMessage()]);
                        }
                    }
                }
            }

            //
            $this->info("Slug:: " . ($index + 1) . "/" . count($useSlugs) . "");
        }


        return 0;
    }

    public function getProducts($keyword, $type = null)
    {

        return $this->getFromKohls($keyword);
        if (rand(0, 1)) {
            //AliExpress API scrapper - 100/month
            $products = $this->getFromAliExpress($keyword);
        } else {
            //Amazon API scrapper - 100/month
            $products = $this->getFromAmazon($keyword);
        }
        logger("Products", [$products]);
        return $products;
    }

    public function getFromAmazon($keyword)
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'free-amazon-data-scraper.p.rapidapi.com',
            'x-rapidapi-key' => '5f43ecf1c8msh055a0e55503f6c6p1d7189jsn529594637e8a'
        ])
            ->get("https://free-amazon-data-scraper.p.rapidapi.com/search/" . $keyword . "?api_key=0fd5b0c1fffb09a1c70c1db4f0afe341");

        logger("response", [$response->json()]);
        if ($response->successful()) {
            return $response->json()["results"];
        } else {
            return [];
        }
    }

    public function getFromAliExpress($keyword)
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'magic-aliexpress1.p.rapidapi.com',
            'x-rapidapi-key' => '5f43ecf1c8msh055a0e55503f6c6p1d7189jsn529594637e8a'
        ])
            ->get("https://magic-aliexpress1.p.rapidapi.com/api/products/search?name=" . $keyword . "");

        logger("response", [$response->json()]);
        if ($response->successful()) {
            $docs = $response->json()["docs"] ?? [];
            $products = [];

            foreach ($docs as $key => $doc) {
                $products[] = [
                    "name" => $doc["product_title"],
                    "price" => $doc["app_sale_price"],
                    "image" => $doc["metadata"]["image"]["imgUrl"],
                ];
            }

            return $products;
        } else {
            return [];
        }
    }

    public function getFromKohls($keyword)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => '767a308f75msh5cd70f578f55d5fp19ee80jsn153858d8633c',
            'X-RapidAPI-Host' => 'kohls.p.rapidapi.com'
        ])
            ->get("https://kohls.p.rapidapi.com/products/list?limit=24&offset=1&keyword=" . $keyword . "&dimensionValueID=");

        // logger("Kohls api response", [$response->json()]);
        if ($response->successful()) {
            $docs = $response->json()["payload"]["products"] ?? [];
            $products = [];

            foreach ($docs as $key => $doc) {
                $price = $doc["prices"][0]["salePrice"] != null ? $doc["prices"][0]["salePrice"]["minPrice"]
                    : $doc["prices"][0]["regularPrice"]["minPrice"];
                //
                $products[] = [
                    "name" => $doc["productTitle"],
                    "price" => $price,
                    "image" => $doc["image"]["url"],
                ];
            }

            return $products;
        } else {
            logger("Kohls api response", [$response->json()]);
            return [];
        }
    }
}
