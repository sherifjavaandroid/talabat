<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Commission;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductReview;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DevDesk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:desk {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run dev commands for some tasks';

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

        $action = $this->argument('action');

        if ($action == "time") {
            if (!\App::environment('production')) {
                //generate random prepare time and delivery time for vendors
                $vendors = Vendor::all();
                foreach ($vendors as $vendor) {

                    //prepare time range or not
                    if (rand(0, 1)) {
                        $vendor->prepare_time = "" . rand(10, 30) . " - " . rand(60, 90) . "";
                    } else {
                        $vendor->prepare_time = "" . rand(20, 90) . "";
                    }

                    //delivery time range or not
                    if (rand(0, 1)) {
                        $vendor->delivery_time = "" . rand(10, 30) . " - " . rand(60, 90) . "";
                    } else {
                        $vendor->delivery_time = "" . rand(20, 90) . "";
                    }

                    //
                    $vendor->save();
                }
            }
        }

        if ($action == "commerce") {
            //generate data to test out e-commerce
            if (!\App::environment('production')) {
                //
                $commerceVendorType = VendorType::where('slug', 'commerce')->first();
                $vendors = Vendor::where('vendor_type_id', $commerceVendorType->id)->get();
                foreach ($vendors as $vendor) {
                    $vendor->delete();
                }


                //create new vendors
                $numberOfVendors = rand(2, 5);
                $faker = \Faker\Factory::create();
                for ($i = 0; $i < $numberOfVendors; $i++) {
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
                    $vendor->vendor_type_id = $commerceVendorType->id;
                    $vendor->save();

                    //
                    try {
                        $vendor->addMediaFromUrl("https://source.unsplash.com/240x240/?logo")->toMediaCollection("logo");
                        $vendor->addMediaFromUrl("https://source.unsplash.com/420x240/?vendor")->toMediaCollection("feature_image");
                    } catch (\Exception $ex) {
                        logger("Error", [$ex->getMessage()]);
                    }

                    //products
                    $productNames = ["T-shirt", 'School bag', 'Ceiling Fan', "air cooler", "baby wear", "fashion", "tech", "gadgets"];

                    $keyword = $productNames[rand(0, count($productNames) - 1)];
                    $productsArray = $this->getProducts($keyword);

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
                            $product->addMediaFromUrl($productObject["image"])->toMediaCollection();
                        } catch (\Exception $ex) {
                            logger("Error", [$ex->getMessage()]);
                        }
                    }
                }
            }
        }

        //recalculate admin commission
        if ($action == "commission") {
            $generalVendorCommission = setting('vendorsCommission', "0");
            $generalDriverCommission = setting('driversCommission', "0");
            $commissions = Commission::get();
            foreach ($commissions as $commission) {
                //admin vendor commission
                if ($commission->order->vendor != null) {
                    $vendorCommission = $commission->order->vendor->commission;
                    if (empty($vendorCommission)) {
                        $vendorCommission = $generalVendorCommission;
                    }
                    //get system commission in amount from the order subtotal
                    $systemCommission = ($vendorCommission / 100) * $commission->order->sub_total;
                    $commission->vendor_commission = $systemCommission;
                }

                //admin driver commission
                if (!empty($commission->order->driver)) {
                    $driver = $commission->order->driver;
                    //
                    if (empty($driver->commission)) {
                        $driver->commission = $generalDriverCommission;
                    }
                    //driver commission from delivery fee + tip from customer
                    if (!empty($commission->order->taxi_order)) {
                        $earnedAmount = ($driver->commission / 100) * $commission->order->total;
                    } else {
                        $earnedAmount = (($driver->commission / 100) * $commission->order->delivery_fee) + $commission->order->tip;
                    }
                    $systemDriverCommission = $commission->order->delivery_fee - $earnedAmount;
                    $commission->driver_commission = $systemDriverCommission > $commission->order->delivery_fee ? $systemDriverCommission : $commission->order->delivery_fee;
                }

                $commission->save();
            }
        }

        //review generate random product rating
        if ($action == "rating") {

            ProductReview::whereNotNull("id")->delete();
            $products = Product::select('id')->get();
            // $products = Product::select('id')->limit(1)->get();
            $orderId = Order::latest()->first()->id ?? 0;
            $reviews = [
                "Bad product",
                "Not what i wanted",
                "Decent enough",
                "Good product, just as expected",
                "No issue at all from purchase to delivery/pickup. Thanks for the great service",
            ];
            //
            foreach ($products as $product) {
                //times the product should be rated
                $times = rand(2, 10);

                for ($i = 0; $i < $times; $i++) {
                    $client = User::select('id')->client()->inRandomOrder()->first();
                    $rating = rand(1, 5);
                    $productReview = new ProductReview();
                    $productReview->user_id = $client->id;
                    $productReview->product_id = $product->id;
                    $productReview->order_id = $orderId;
                    $productReview->rating = $rating;
                    $productReview->review = $reviews[$rating - 1];
                    $productReview->save();
                }
            }
        }

        return 0;
    }

    public function getProducts($keyword)
    {

        //AliExpress API scrapper - 100/month
        return $this->getFromAliExpress($keyword);

        //Amazon API scrapper - 100/month
        return $this->getFromAmazon($keyword);
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
            $docs = $response->json()["docs"];
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
}
