<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteExtensionFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-extension-folders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unneed extensions installation folders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $paths = [
            "app/Http/Livewire/Extensions",
            "resources/views/livewire/extensions",
            "public/css/extensions",
            "public/js/extensions",
        ];

        foreach ($paths as $path) {
            if ($this->confirm("Delete Empty Folders in this path: {$path}. Do you wish to continue?")) {
                $path = base_path($path);
                $this->deleteEmptyFoldersRecursively($path);
            }
        }
    }


    function deleteEmptyFoldersRecursively($parentPath)
    {

        if (File::exists($parentPath) && File::isDirectory($parentPath)) {
            $directories = File::directories($parentPath);

            foreach ($directories as $directory) {
                // Recursively delete empty subfolders
                $this->deleteEmptyFoldersRecursively($directory);
            }

            // Check if the current directory is empty after deleting empty subdirectories
            $files = File::files($parentPath);
            $subDirectories = File::directories($parentPath);

            if (empty($files) && empty($subDirectories)) {
                File::deleteDirectory($parentPath);
                echo "Deleted empty folder: $parentPath\n";
            }
        } else {
            echo "Path does not exist or is not a directory: $parentPath\n";
        }
    }
}