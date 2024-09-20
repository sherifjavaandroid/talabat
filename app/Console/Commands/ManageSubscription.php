<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ManageSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage subscription when the time is overdue';

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

        //get all active vendor with use_subscription enabled
        $vendors = Vendor::active()->where('use_subscription', 1)->get();
        //disable any that doesn't have subscription
        foreach ($vendors as $vendor) {
            //if all previous subscription as pass due data
            if (!$vendor->has_subscription) {
                $vendor->is_active = false;
                $vendor->save();
            }
        }
    }
}
