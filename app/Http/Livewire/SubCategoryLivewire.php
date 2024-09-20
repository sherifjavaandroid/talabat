<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Subcategory;

class SubCategoryLivewire extends BaseLivewireComponent
{

    //
    public $model = Subcategory::class;

    //
    public $name;
    public $category_id;
    public $isActive;
    public $categories;

    protected $rules = [
        "name" => "required|string",
        "category_id" => "required|exists:categories,id",
    ];

    public function mount()
    {
        $this->categories = Category::get();
    }

    public function render()
    {
        if ($this->categories == null || empty($this->categories)) {
            $this->categories = Category::get();
        }
        return view('livewire.subcategories');
    }

    public function showCreateModal()
    {
        $this->showCreate = true;
        $this->category_id = Category::get()->first()->id ?? null;
    }

    public function save()
    {
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.sub_category", 300) . "";
        $this->validate($rules);

        try {

            DB::beginTransaction();
            $model = new Subcategory();
            $model->name = $this->name;
            $model->is_active = $this->isActive;
            $model->category_id = $this->category_id ?? $this->categories->first()->id ?? null;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->resetExcept("categories");
            $this->showSuccessAlert(__("Subcategory") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Subcategory") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->isActive = $this->selectedModel->is_active;
        $this->category_id = $this->selectedModel->category_id ?? $this->categories->first()->id ?? null;
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.sub_category", 300) . "";
        $this->validate($rules);

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->is_active = $this->isActive;
            $model->category_id = $this->category_id;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            //reset except categories
            $this->resetExcept("categories");
            $this->showSuccessAlert(__("Subcategory") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Subcategory") . " " . __('updated failed!'));
        }
    }
}
