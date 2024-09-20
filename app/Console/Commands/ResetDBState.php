<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetDBState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:database:state {v} {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear demo database to prepare for export';

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

        $appVerisonCode = $this->argument('code');
        $appVerison = $this->argument('v');
        \Artisan::call('config:clear');
        $this->info('Done clearing config');
        \Artisan::call('view:clear');
        $this->info('Done clearing view');
        \Artisan::call('cache:clear');
        $this->info('Done clearing cache');
        \Artisan::call('migrate:fresh --seed --force');
        $this->info('Done migrating database');

        //sync clean settings seeder
        \Artisan::call('db:seed --class=SettingsTableSeeder --force');
        \Artisan::call('db:seed --class=FaqsTableSeeder --force');
        //
        \DB::table('settings')
            ->where('key', "appVerisonCode")
            ->update(['value' => $appVerisonCode]);
        \DB::table('settings')
            ->where('key', "appVerison")
            ->update(['value' => $appVerison]);

        //
        \Artisan::call('reset:settings');
        $this->info('Done setting app version');
        return 0;
    }
}
