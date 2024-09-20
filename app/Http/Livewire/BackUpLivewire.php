<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class BackUpLivewire extends BaseLivewireComponent
{

    protected $listeners = [
        'deleteModel',
        'refreshTable' => '$refresh',
    ];


    public function render()
    {

        $files = Storage::allFiles(env("APP_NAME"));
        //reverse the array so that the latest backup is shown first
        $files = array_reverse($files);
        return view('livewire.backup', [
            "backups" => $files,
        ]);
    }

    public function newBackUp()
    {

        try {

            Artisan::call("backup:run --only-db");
            $this->showSuccessAlert(__("Database backup successful"));
        } catch (Exception $error) {

            $this->showErrorAlert(__("Database backup failed"));
        }
    }


    public function initiateDelete($file)
    {

        $this->selectedModel = $file;
        $this->confirm('Delete', [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to delete the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'onConfirmed' => 'deleteModel',
            'onCancelled' => 'cancelled'
        ]);
    }


    public function deleteModel()
    {

        try {

            Storage::delete($this->selectedModel);
            $this->showSuccessAlert(__("Backup Deleted"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Backup delete Failed"));
        }
    }


    public function downloadBackup($file)
    {
        return Storage::download($file);
    }
}
