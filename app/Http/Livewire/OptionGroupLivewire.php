<?php

namespace App\Http\Livewire;

use App\Models\OptionGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OptionGroupLivewire extends BaseLivewireComponent
{

    //
    public $model = OptionGroup::class;

    //
    public $name;
    public $multiple = 1;
    public $required = 0;
    public $isActive = 1;
    public $max_options;

    protected $rules = [
        "name" => "required|string",
    ];

    public function render()
    {
        return view('livewire.options-group');
    }



    public function showCreateModal()
    {

        if (\Auth::user()->hasAnyRole('manager')) {
            $this->showCreate = true;
        } else {
            $this->showWarningAlert(__("Only vendor manager can create new record"));
        }
    }


    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new OptionGroup();
            $model->name = $this->name;
            $model->multiple = $this->multiple;
            $model->is_active = $this->isActive;
            $model->required = $this->required ?? false;
            $model->vendor_id = Auth::user()->vendor_id;
            $model->max_options = $this->max_options;
            $model->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Option Group") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Option Group") . " " . __('creation failed!'));
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->isActive = $this->selectedModel->is_active;
        $this->required = $this->selectedModel->required ?? false;
        $this->multiple = $this->selectedModel->multiple;
        $this->max_options = $this->selectedModel->max_options;
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->multiple = $this->multiple;
            $model->is_active = $this->isActive;
            $model->required = $this->required ?? false;
            $model->max_options = $this->max_options;
            $model->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Option Group") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Option Group") . " " . __('updated failed!'));
        }
    }
}
