<?php

namespace App\Http\Livewire\Tables;

use Exception;
use Kdion4891\LaravelLivewireTables\TableComponent;
use Illuminate\Support\Facades\App;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BaseTableComponent extends TableComponent
{
    use LivewireAlert;

    public $canManage = true;
    public $checkDemo = false;

    protected $listeners = [
        'activateModel',
        'deactivateModel',
        'deleteModel',
        'filterUsers',
        'refreshTable' => '$refresh',
    ];

    //
    public function thClass($attribute)
    {
        return 'p-2';
    }

    public function tdClass($attribute, $value)
    {
        if ($attribute == 'session_id') return 'break-all p-2 w-2/12';
        if ($attribute == 'link') return 'break-all p-2 w-2/12';
        if ($attribute == 'description') return 'break-all p-2 w-3/12';
        if ($attribute == 'name') return 'break-all p-2 w-2/12';
        return "p-2";
    }

    public function trClass($model)
    {
        return 'border-b';
    }

    //Alert
    public function showSuccessAlert($message = "")
    {
        $this->alert('success', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
        ]);
    }

    public function showWarningAlert($message = "")
    {
        $this->alert('warning', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
        ]);
    }

    public function showErrorAlert($message = "")
    {
        $this->alert('error', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
        ]);
    }
    //End Alert



    public $selectedModel;
    public $model;

    public function initiateActivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Activate'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to activate the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'activateModel',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function initiateDeactivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Deactivate'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to deactivate the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'deactivateModel',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function initiateDelete($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Delete'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to delete the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'deleteModel',
            'onCancelled' => 'cancelled'
        ]);
    }


    public function activateModel()
    {

        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            $this->selectedModel->is_active = true;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Activated"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }


    public function deactivateModel()
    {

        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            $this->selectedModel->is_active = false;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Deactivated"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }

    public function deleteModel()
    {

        try {
            $this->isDemo();
            $this->selectedModel->delete();
            $this->showSuccessAlert(__("Deleted"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }




    public function isDemo()
    {
        if (!App::environment('production')) {
            throw new Exception(__("App is in demo version. Some changes can't be made"));
        };
    }

    public function inDemo()
    {
       return !App::environment('production');
    }
}
