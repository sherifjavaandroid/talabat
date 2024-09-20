<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DevPopulateCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:gen:cat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev populate category with images';

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

        //generate data to test 
        $categories = Category::get();
        foreach ($categories as $category) {

            try {
                $category->addMediaFromUrl("https://source.unsplash.com/420x240/?vendor,{$category->name}")
                    ->toMediaCollection();
            } catch (\Exception $ex) {
                logger("unsplash Error", [$ex->getMessage()]);
            }
        }
        //
        $categories = SubCategory::get();
        foreach ($categories as $category) {

            try {
                $category->addMediaFromUrl("https://source.unsplash.com/420x240/?vendor,{$category->name}")
                    ->toMediaCollection();
            } catch (\Exception $ex) {
                logger("unsplash Error", [$ex->getMessage()]);
            }
        }


        return 0;
    }
}
