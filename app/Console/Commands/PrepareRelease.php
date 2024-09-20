<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PrepareRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will setup database for the exporting for next update';

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
        return 0;
    }
}
