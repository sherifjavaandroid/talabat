<?php

namespace App\Http\Livewire;

use App\Models\Extension;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ExtensionLivewire extends BaseLivewireComponent
{

    public $extensions;

    protected $listeners = [
        'showCreateModal' => 'showCreateModal',
        'hideExtensions' => 'hideExtensions',
        'showExtensions' => 'showExtensions',
        'extensionUploaded' => 'extensionUploaded',
        'dismissModal' => 'dismissModal',
    ];

    public function mount()
    {
        $this->showExtensions();
    }
    public function render()
    {
        $this->extensions = Extension::all();
        return view('livewire.extensions.index');
    }

    public function dismissModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->reset();
    }


    public function hideExtensions()
    {
        $this->showDetails = false;
    }

    public function showExtensions()
    {
        $this->showDetails = true;
    }




    //
    public function installExtension()
    {
        try {

            $this->isDemo();

            if ($this->photo == null) {
                $this->showWarningAlert(__('Please select extension file'));
                return;
            }


            //
            //store the zip
            $fileLocation = $this->uploadZipFile($this->photo);
            //unzip the file to a temp folder
            $extractedFileLocation = $this->extractZipFile($fileLocation);
            //
            $extensionFolder = $this->newExtensionFolder($extractedFileLocation . "/extension");
            //move the conect of the files to extensions in http flder
            $extensionComponetLocation = $this->moveExtensionFiles($extensionFolder);
            logger("extensionComponetLocation", [$extensionComponetLocation]);
            //call run function in the installer class
            $extensionInstaller = "App\\Http\\Livewire\\Extensions\\" . $extensionComponetLocation . "\\Installer";
            if (!class_exists($extensionInstaller)) {
                $this->showErrorAlert(__("Extension Installation faild!"));
                return;
            }
            $extensionInstallerClass = new $extensionInstaller();
            $extensionInstallerClass->run();

            //
            $this->showSuccessAlert(__("Extension Installation Successful!"));
        } catch (\Exception $ex) {
            logger("Extension Installation", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Extension Installation") . " " . __('failed!'));
        }
    }


    //
    public function uploadZipFile($file)
    {
        $code = \Str::random(20);
        $extensionFolder = "extensions/" . $code;
        //
        $file->storeAs($extensionFolder, "extension.zip");
        return $extensionFolder;
    }

    //
    public function moveExtensionFiles($extensionFolder)
    {
        $extensionComponetLocation = "";
        //
        $files = scandir($extensionFolder);
        foreach ($files as $file) {
            //move extion folders
            switch ($file) {
                case 'js':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/public/js/extensions";
                    $this->copyFile($from, $to);
                    break;

                case 'css':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/public/css/extensions";
                    $this->copyFile($from, $to);
                    break;

                case 'images':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/public/images/extensions";
                    $this->copyFile($from, $to);
                    break;

                case 'view':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/resources/views/livewire/extensions";
                    $this->copyFile($from, $to);
                    break;

                case 'extension':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/app/Http/Livewire/Extensions";
                    $this->copyFile($from, $to);

                    $files = scandir($extensionFolder . "/" . $file);
                    foreach ($files as $file) {
                        if ($file[0] != '.') {
                            $extensionComponetLocation = $file;
                            break;
                        }
                    }
                    //
                    break;
                case 'vendor':
                    $from = $extensionFolder . "/" . $file . "/.";
                    $to = base_path() . "/vendor";
                    $this->copyFile($from, $to);
                    break;

                default:
                    # code...
                    break;
            }
            //move extion folders

        }
        return $extensionComponetLocation;
    }

    public function copyFile($from, $to)
    {
        $process = new Process(explode(" ", "cp -a " . $from . " " . $to . ""));
        $process->setWorkingDirectory(base_path() . "/");
        $process->run();
    }

    //
    public function newExtensionFolder($location)
    {
        $foldFound = "";
        //
        $files = scandir($location);
        foreach ($files as $file) {
            if (!in_array($file, [".", "..", "__MACOSX"])) {
                $foldFound = $file;
                break;
            }
        }

        return $location . "/" . $foldFound;
    }

    public function extractZipFile($location)
    {


        $workDir = storage_path() . "//app//" . $location;

        //
        try {
            //update permission
            $process = new Process(explode(" ", "chmod 777 extension.zip"));
            $process->setWorkingDirectory($workDir);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                logger("chmod Process error", [new ProcessFailedException($process)]);
            } else {
                // logger("chmod Process output", [$process->getOutput()]);
                logger("chmod Process output", ["Done"]);
            }


            //uunzip
            $process = new Process(explode(" ", "unzip extension.zip -d extension"));
            $process->setWorkingDirectory($workDir);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                logger("Unzip Process error", [new ProcessFailedException($process)]);
            } else {
                // logger("Unzip Process output", [$process->getOutput()]);
                logger("Unzip Process output", ["Done"]);
            }
            // Madzipper::make($appBaseFolder . "/app.zip")->extractTo($extractedFolder);
            logger("Done Extract", ["Yes"]);
        } catch (\Exception $ex) {
            logger("Unzip", [$ex->getMessage()]);
            $process = new Process(explode(" ", "unzip extension.zip -d extension"));
            $process->setWorkingDirectory($workDir);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                logger("Unzip Process error", [new ProcessFailedException($process)]);
            } else {
                // logger("Unzip Process output", [$process->getOutput()]);
                logger("Unzip Process output", ["Done"]);
            }
        }


        //
        return $workDir;
    }
}
