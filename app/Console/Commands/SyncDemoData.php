<?php

namespace App\Console\Commands;

use App\Traits\DBTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SyncDemoData extends Command
{

    use DBTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-demo-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        //return if in production
        if (config('app.env') == 'production') {
            //ask for confirmation
            $approved = $this->confirm('This command is not allowed in production. Are you sure you want to continue?');
            if (!$approved) {
                $this->error('This command is not allowed in production');
                return;
            }
        }

        //truncate tables
        $this->truncateTables();

        //new line
        $this->newLine();

        //call the seeder
        $seeders = [
            "DemoFoodVendorSeeder",
            "DemoGroceryVendorSeeder",
            "DemoPharmacyVendorSeeder",
            "DemoShoppingVendorSeeder",
            "DemoServiceVendorSeeder",
            "DemoCategorySeeder",
            "DemoBannerSeeder",
        ];

        //loop through the seeders
        foreach ($seeders as $seeder) {
            $this->info("Seeding $seeder");
            //call the db:seed command then pass the seeder name
            Artisan::call('db:seed', [
                '--class' => $seeder,
                '--force' => true,
            ]);
            $this->info("Seeding $seeder completed");
        }
    }


    //MISC.
    public function truncateTables()
    {
        // DB::statement("SET foreign_key_checks=0");
        $tables = [
            'payments',
            'statuses',
            'refunds',
            'order_products',
            'order_stops',
            'taxi_orders',
            'remittances',
            'commissions',
            'orders',
            'auto_assignments',
            'earning_reports',
            'payouts',
            'earnings',
            'earneds',
            'banners',
            'category_product',
            'categories',
            'city_vendor',
            'fee_vendor',
            'country_vendor',
            'flash_sales',
            'menu_product',
            'menus',
            'products',
            'vendors',
        ];

        //progress bar
        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();
        foreach ($tables as $table) {

            try {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    //set back the auto increment to 1
                    DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1");
                }
            } catch (\Exception $error) {
                // logger("Error Truncating $table", [$error]);
                if (Schema::hasTable($table)) {
                    DB::table($table)->delete();
                    //set back the auto increment to 1
                    DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1");
                }
            }
            $bar->advance();
        }
        $bar->finish();

        //clear photos of orders
        $this->clearMedia(["App\Models\Order", "App\Models\OrderStop"]);
        $this->clearOldMedia();
        // $this->clearTableRecords("order_id", false);
        // $this->clearTableRecords("vendor_id", false);
        // DB::statement("SET foreign_key_checks=1");
    }




    public function clearOldMedia()
    {
        //
        try {
            $keepMediaType = ["App\Models\Product", "App\Models\Service", "App\Models\Payment"];
            $mediaSet = Media::whereNotIn('model_type', $keepMediaType)->get();
            foreach ($mediaSet as $media) {
                $keep = Media::where('model_type', $media->model_type)
                    ->where("model_id", $media->model_id)
                    ->where("collection_name", $media->collection_name)
                    ->latest()
                    ->take(1)
                    ->pluck('id');

                Media::where('model_type', $media->model_type)
                    ->where("model_id", $media->model_id)
                    ->where("collection_name", $media->collection_name)
                    ->whereNotIn('id', $keep)
                    ->delete();
            }
        } catch (\Exception $error) {
            logger("Error", [$error]);
        }
    }


    //clear media
    public function clearMedia(array $models)
    {
        //clear media
        DB::table('media')->whereIn("model_type", $models)->delete();
    }
}
