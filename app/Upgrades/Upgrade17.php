<?php

namespace App\Upgrades;

use App\Models\VendorType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Upgrade17 extends BaseUpgrade
{   

    public $versionName = "1.3.2";
    //Runs or migrations to be done on this version
	public function run(){

         //vendor min/max orders
         if (!Schema::hasColumn('vendors', 'min_order')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->double('min_order',15,2)->nullable()->after('has_sub_categories');
                $table->double('max_order',15,2)->nullable()->after('min_order');
            });
        }

        //alter vendors amounts
        \DB::statement("ALTER TABLE vendors MODIFY COLUMN `base_delivery_fee` double(15,2)");
        \DB::statement("ALTER TABLE vendors MODIFY COLUMN `delivery_fee` double(15,2)");

        //alter orders amounts
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `sub_total` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `tip` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `discount` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `delivery_fee` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `commission` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `tax` double(15,2)");
        \DB::statement("ALTER TABLE orders MODIFY COLUMN `total` double(15,2)");
        //
        \DB::statement("ALTER TABLE wallets MODIFY COLUMN `balance` double(15,2)");
        \DB::statement("ALTER TABLE products MODIFY COLUMN `price` double(15,2)");
        \DB::statement("ALTER TABLE products MODIFY COLUMN `discount_price` double(15,2)");


        //alter reviews
        \DB::statement("ALTER TABLE `reviews` CHANGE `vendor_id` `vendor_id` BIGINT(20) UNSIGNED NULL");
        \DB::statement("ALTER TABLE `reviews` CHANGE `driver_id` `driver_id` BIGINT(20) UNSIGNED NULL");
        //product
        Schema::table('products', function (Blueprint $table) {
            $table->string('capacity')->default("1")->nullable()->change();
        });


        //vendor type
        $vendorType = VendorType::where('slug', "service")->first();
        if (empty($vendorType)) {
            \DB::table('vendor_types')->insert(array(
                0 =>
                array(
                    'name' => 'Services',
                    'description' => 'for vendor selling services',
                    'slug' => 'service',
                    'is_active' => 0,
                    'created_at' => '2021-07-15 00:38:10',
                    'created_at' => '2021-07-15 00:38:10',
                ),
            ));
        }


        //force migration for new tables
        if (!Schema::hasTable('remittances')) {
            Artisan::call('migrate --path=database/migrations/2021_07_19_003627_create_remittances_table.php --force');
        }

        //fix the firebase server worker
        $file_name = base_path()."/public/firebase-messaging-sw.js";
        $this->fileEditContents($file_name, "11", "messagingSenderId: '".setting("messagingSenderId","")."',");

    }


}