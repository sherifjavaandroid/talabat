<?php

namespace App\Http\Livewire;


use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceLivewire extends BaseLivewireComponent
{

    //
    public $model = Service::class;

    //
    public $name;
    public $description;
    public $price;
    public $discount_price;
    public $vendor_id;
    public $duration;
    public $location;
    public $is_active;
    public $age_restricted = 0;
    public $category_id;
    public $subcategory_id;
    public $photos = [];
    public $durationTypes = [];
    public $subcategories = [];

    //
    public $vendorSearchClause = ["id" => 0];
    public $categorySearchClause = ["id" => 0];


    protected $rules = [
        "name" => "required|string",
        "description" => "sometimes|nullable|string",
        "price" => "required|numeric",
        "discount_price" => "sometimes|nullable|numeric",
        "vendor_id" => "required|exists:vendors,id",
        "category_id" => "required|exists:categories,id",
        "subcategory_id" => "nullable|sometimes|exists:subcategories,id",
        "duration" => "required",
        "is_active" => "sometimes",
        "location" => "sometimes",
    ];


    protected $messages = [
        "vendor_id.exists" => "Invalid vendor selected",
        "category_id.exists" => "Invalid category selected",
        "subcategory_id.exists" => "Invalid subcategory selected",
    ];

    public function mount()
    {
        $user = User::find(Auth::id());

        if ($user->hasRole('admin')) {
            $this->vendorSearchClause = [];
        } else {
            $this->vendor_id = Auth::user()->vendor_id;
            $this->vendorSearchClause = ['id' => Auth::user()->vendor_id];
        }
    }

    public function render()
    {
        if (empty($this->durationTypes)) {
            $this->durationTypes = Service::getPossibleEnumValues('duration');
            $this->duration = $this->durationTypes[0];
        }
        return view('livewire.services.service');
    }

    //
    public function autocompleteVendorSelected($vendor)
    {
        $this->vendor_id = $vendor["id"];
    }
    public function autocompleteCategorySelected($category)
    {
        $this->category_id = $category["id"];
        $this->subcategories = Subcategory::whereCategoryId($this->category_id)->get();
    }


    public function showCreateModal()
    {
        $this->resetErrorBag();
        $this->vendor_id = Auth::user()->vendor_id ?? null;
        $this->showCreate = true;
        $this->stopRefresh = true;
        $this->emit('loadSummerNote', "newContent", "");
    }

    public function save()
    {

        if (empty($this->subcategory_id)) {
            $this->subcategory_id = null;
        }
        //validate
        $allData = $this->validate();

        try {

            DB::beginTransaction();
            $model = new Service($allData);
            $model->discount_price = $model->discount_price == null ? 0 : $model->discount_price;
            $model->age_restricted = $this->age_restricted;
            $model->save();

            if ($this->photos) {

                $model->clearMediaCollection();
                foreach ($this->photos as $photo) {
                    $model->addMedia($photo)->toMediaCollection();
                }
                $this->photos = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Service") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Service") . " " . __('creation failed!'));
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->price = $this->selectedModel->price;
        $this->discount_price = $this->selectedModel->discount_price;
        $this->vendor_id = $this->selectedModel->vendor_id;
        $this->duration = $this->selectedModel->duration;
        $this->is_active = $this->selectedModel->is_active;
        $this->location = $this->selectedModel->location;
        $this->category_id = $this->selectedModel->category_id;
        $this->autocompleteCategorySelected(["id" => $this->category_id]);
        $this->subcategory_id = $this->selectedModel->subcategory_id;
        $this->age_restricted = $this->selectedModel->age_restricted;

        //load summernote with selected product description
        $this->emit('loadSummerNote', "editContent", $this->description);
        $this->emit('preselectedVendorEmit', \Str::ucfirst($this->selectedModel->vendor->name ?? ''));
        $this->emit('preselectedCategoryEmit', \Str::ucfirst($this->selectedModel->category->name ?? ''));
        $this->emit('showEditModal');
    }

    public function update()
    {
        if (empty($this->subcategory_id)) {
            $this->subcategory_id = null;
        }
        //validate
        $allData = $this->validate();

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->fill($allData);
            $model->age_restricted = $this->age_restricted;
            $model->save();

            if ($this->photos) {

                $model->clearMediaCollection();
                foreach ($this->photos as $photo) {
                    $model->addMedia($photo)->toMediaCollection();
                }
                $this->photos = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Service") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Service") . " " . __('updated failed!'));
        }
    }


    //
    public function photoSelected($photos)
    {
        $this->photos = $photos;
    }
}
