<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearSettingsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear un needed settings value from database';

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
        $keysToDelete = [
            "googleMapKey", "apiKey", "projectId", "messagingSenderId", "appId", "vapidKey", "billzCollectionId",
            "serverFBAuthToken",
            "serverFBAuthTokenExpiry",
            "sms_gateways",
            "serviceKeyPath",
            "websiteLogo",
            "favicon",
            "loginImage",
            "registerImage",
            "websiteHeaderImage",
            "websiteFooterBrief",
            "websiteFooterImage",
            "websiteIntroImage",
            "external_notifier",
        ];
        $this->withProgressBar($keysToDelete, function ($key) {
            logger("Deleting $key");
            $values = \DB::table('settings')
                ->where('key', $key)
                ->orWhere('key', "LIKE", "%$key%")
                ->get();
            logger("Found values: ", $values->pluck('value')->toArray());
            \DB::table('settings')
                ->where('key', $key)
                ->orWhere('key', "LIKE", "%$key%")
                ->delete();
        });
        \Artisan::call('iseed settings --force');
        $this->info('Done clearing settings');
        return 0;
    }
}
