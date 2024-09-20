<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean broken media paths';

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

        $mediaSet = Media::get();
        foreach ($mediaSet as $media) {
            $path = storage_path("app/public/{$media->id}/{$media->file_name}");
            if(!file_exists($path)){
                $media->delete();
            }

        }
        return 0;
    }
}
