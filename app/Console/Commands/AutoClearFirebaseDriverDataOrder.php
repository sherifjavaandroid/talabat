<?php

namespace App\Console\Commands;

use App\Traits\OrderFCMTrait;
use Illuminate\Console\Command;


class AutoClearFirebaseDriverDataOrder extends Command
{

    use OrderFCMTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:driver:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all pending orders from driver node on firebase';

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
        //
        $this->clearDriverNewOrderFirestore();

    }
}
