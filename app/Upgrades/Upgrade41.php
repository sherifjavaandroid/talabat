<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Upgrade41 extends BaseUpgrade
{

    public $versionName = "1.5.8";
    //Runs or migrations to be done on this version
    public function run()
    {


        if (!Schema::hasTable('flash_sales')) {
            Artisan::call('migrate --path=database/migrations/2022_06_16_081754_create_flash_sales_table.php --force');
        }

        if (!Schema::hasTable('flash_sale_items')) {
            Artisan::call('migrate --path=database/migrations/2022_06_16_083127_create_flash_sale_items_table.php --force');
        }
    }
    /*
        $tableForeignKeys = [
            "vendors" => "creator_id",
            "coupons" => "creator_id",
            "auto_assignments" => "order_id",
            "auto_assignments" => "driver_id",
            "vehicles" => "driver_id",
            "vehicles" => "vehicle_type_id",
            "earneds" => "order_id",
            "earneds" => "driver_id",
            "earneds" => "vendor_id",
            "referrals" => "user_id",
            "referrals" => "referred_user_id",
            "delivery_addresses" => "user_id",
            "orders" => "vendor_id",
            "orders" => "user_id",
            "orders" => "driver_id",
            "favourites" => "user_id",
            "favourites" => "product_id",
            "earnings" => "user_id",
            "earnings" => "vendor_id",
            "payouts" => "earning_id",
            "payouts" => "user_id",
            "user_tokens" => "user_id",
            "wallets" => "user_id",
            "remittances" => "user_id",
            "remittances" => "order_id",
            "push_notifications" => "user_id",
            "referrals" => "user_id",
            "referrals" => "referred_user_id",
            "product_reviews" => "product_id",
            "product_reviews" => "order_id",
            "product_reviews" => "user_id",
        ];


        $nullables = [
            "vendors_creator_id",
            "reviews_driver_id",
            "orders_driver_id",
            "coupons_creator_id",
            "earneds_driver_id",
            "earneds_vendor_id",
        ];


        try {
            //
            \DB::beginTransaction();
            //update the ondelete constriants
            foreach ($tableForeignKeys as $tableName => $column) {
                if (Schema::hasColumn($tableName, $column)) {

                    $copyColumn = "copy_" . $column . "";

                    //
                    if ($this->isFK($tableName, $column)) {
                        Schema::table($tableName, function ($table) use ($column, $copyColumn) {
                            logger("droping FK");
                            $table->dropForeign([$column]);
                        });
                    }

                    //
                    Schema::table($tableName, function ($table) use ($column, $copyColumn) {
                        $table->renameColumn($column, $copyColumn);
                    });

                    //
                    // $isNullable = in_array("" . $tableName . "_" . $column . "", $nullables);
                    // if ($isNullable) {
                    //     DB::table($tableName)->where($column, 0)->update([
                    //         $column => DB::raw($copyColumn)
                    //     ]);
                    // }

                    //
                    Schema::table($tableName, function ($table) use ($tableName, $column, $nullables) {
                        $foreignTable = null;
                        $isNullable = in_array("" . $tableName . "_" . $column . "", $nullables);
                        //

                        if (in_array($column, ['driver_id', 'creator_id'])) {
                            $foreignTable = "users";
                        }

                        //new coumn
                        if ($isNullable) {
                            $table->foreignId($column)->nullable()->constrained($foreignTable)->onDelete('cascade');
                        } else {
                            $table->foreignId($column)->constrained($foreignTable)->onDelete('cascade');
                        }
                    });

                    //copy data from copyColumn to new column
                    //Copy the data across to the new column:
                    \DB::table($tableName)->update([
                        $column => \DB::raw($copyColumn)
                    ]);

                    //Remove the old column:
                    Schema::table($tableName, function ($table) use ($copyColumn) {
                        $table->dropColumn($copyColumn);
                    });
                }
            }
            //
            \DB::rollback();
            // \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            logger("Upgrade error", [$ex]);
        }
    }



    function isFK(string $table, string $column): bool
    {
        $fkColumns = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableForeignKeys($table);

        return collect($fkColumns)->map(function ($fkColumn) {
            return $fkColumn->getColumns();
        })->flatten()->contains($column);
    }
    */
}
