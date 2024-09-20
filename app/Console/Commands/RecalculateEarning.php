<?php

namespace App\Console\Commands;

use App\Models\EarningReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RecalculateEarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 're:earnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcuate earnings ';

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

        //
        $generalVendorCommission = setting('vendorsCommission', "0");
        $generalDriverCommission = setting('driversCommission', "0");
        //
        $earnings = EarningReport::get();
        foreach ($earnings as $earning) {
            $percentage = 0;
            if (!empty($earning->earnings->user)) {
                //vendor
                $percentage = $earning->earnings->user->commission;
                if (empty($percentage)) {
                    $percentage = $generalDriverCommission;
                }

                $percentage = (100 - $percentage);
            } else {
                //vendor
                $percentage = $generalVendorCommission;
                if (empty($percentage) && !empty($earning->earnings->vendor)) {
                    $percentage = $earning->earnings->vendor->commission;
                }
            }
            //
            $calculatedCommission = ($percentage / 100) * $earning->earning;
            $earning->commission = $calculatedCommission;
            $earning->balance = $earning->earning - $calculatedCommission;
            $earning->save();
        }
    }
}
