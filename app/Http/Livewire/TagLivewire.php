<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use App\Models\VendorType;

class TagLivewire extends BaseLivewireComponent
{

    //
    public $model = Tag::class;
    public $name;
    public $vendor_type_id;
    public $types = [];


    protected $rules = [
        "name" => "required|string",
    ];


    public function render()
    {
        if (empty($this->types)) {
            $this->types = VendorType::distinct()->whereNotIn('slug', ['parcel', 'package', 'taxi'])->get();
        }
        return view('livewire.tag');
    }

    public function save()
    {
        //validate
        $this->validate();

        try {
            $this->isDemo();
            DB::beginTransaction();
            $model = new Tag();
            $model->name = $this->name;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Tag") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Tag") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->vendor_type_id = $this->selectedModel->vendor_type_id;
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {
            $this->isDemo();
            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Tag") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Tag") . " " . __('updated failed!'));
        }
    }
}
