<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use App\Models\Order;

class Upgrade44 extends BaseUpgrade
{

    public $versionName = "1.6.0";
    //Runs or migrations to be done on this version
    public function run()
    {


        //fix order fees duplicates
        $orders = Order::whereNotNull("fees")->where("fees", "!=", "[]")->get();
        foreach ($orders as $order) {
            //
            $feesCollection = collect(json_decode($order->fees, true));
            $uniqueFeesCollection = $feesCollection->unique('id');
            $fees = $uniqueFeesCollection->values()->all();
            $order->fees = json_encode($fees);
            $order->save();
        }

        //subcategory_id to services
        if (!Schema::hasColumn("services", 'subcategory_id')) {
            Schema::disableForeignKeyConstraints();
            Schema::table("services", function ($table) {
                $table->foreignId('subcategory_id')->constrained('subcategories')->nullable()->after('category_id');
            });
            Schema::enableForeignKeyConstraints();
        }
    }
}
