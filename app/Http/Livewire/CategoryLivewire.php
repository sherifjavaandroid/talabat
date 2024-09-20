<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\VendorType;

class CategoryLivewire extends BaseLivewireComponent
{

    //
    public $model = Category::class;

    //
    public $name;
    public $color;
    public $isActive;
    public $vendor_type_id;


    public $categories;
    public $vendorTypes;

    protected $rules = [
        "name" => "required|string",
    ];


    public function render()
    {
        $this->vendorTypes = VendorType::active()->get();
        return view('livewire.categories');
    }

    public function save()
    {
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.category", 300) . "";
        $this->validate($rules);


        try {

            DB::beginTransaction();
            $model = new Category();
            $model->name = $this->name;
            $model->color = $this->color ?? "#000000";
            $model->is_active = $this->isActive ?? false;
            $model->vendor_type_id = $this->vendor_type_id ?? $this->vendorTypes->first()->id;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Category") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Category") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->color = $this->selectedModel->color;
        $this->isActive = $this->selectedModel->is_active;
        $this->vendor_type_id = $this->selectedModel->vendor_type_id ?? $this->vendorTypes->first()->id ?? '';
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.category", 300) . "";
        $this->validate($rules);

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->color = $this->color ?? "#000000";
            $model->is_active = $this->isActive ?? false;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Category") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Category") . " " . __('updated failed!'));
        }
    }
}
