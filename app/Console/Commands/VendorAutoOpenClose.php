<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use App\Models\VendorOpenStatus;

class VendorAutoOpenClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:auto:open-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically open and close vendor shop when vendor has days to open and close shop';

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

        //if table vendor_open_statuses does not exist, return
        if (!\Schema::hasTable('vendor_open_statuses')) {
            return 0;
        }

        //fetch vendors where has days
        $vendors = Vendor::whereHas('days')
            //get only id, days and is_open
            ->select('id', 'is_open')
            ->with('days')
            ->get();

        //loop through vendors
        foreach ($vendors as $vendor) {

            // get the vendor's open status from the vendor_open_statuses table
            $vendorOpenStatus = VendorOpenStatus::whereVendorId($vendor->id)->first();
            if ($vendorOpenStatus != null && $vendorOpenStatus->auto_set != true) {
                // if the vendor's open status was set manually, skip the vendor
                //if the vendor's open status is created 12 hours ago, auto set to true
                $maxTimeHours = (int) setting('vendorResetOpenCloseTime', 2);
                if ($vendorOpenStatus->created_at->diffInHours(now()) >= $maxTimeHours) {
                    $vendorOpenStatus->auto_set = true;
                    $vendorOpenStatus->saveQuietly();
                } else {
                    continue;
                }
            }

            if (count($vendor->openToday) > 0) { // check if the vendor is open today
                $vendor->is_open = count($vendor->openNow) > 0;
            } else {
                $vendor->is_open = false; // set vendor's open status to false if the vendor is not open today
            }
            $vendor->saveQuietly(); // save the vendor's open status
            //
            $vendorOpenStatus = VendorOpenStatus::updateOrCreate(
                ['vendor_id' => $vendor->id],
                [
                    'is_open' => $vendor->is_open,
                    'auto_set' => true
                ]
            ); // update or create the vendor's open status in the vendor_open_statuses table
        }

        $this->info('Vendor open status updated successfully.');

        return 0;
    }
}
