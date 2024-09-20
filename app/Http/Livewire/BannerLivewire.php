<?php

namespace App\Http\Livewire;

use App\Models\Banner;
use App\Models\VendorType;
use Exception;
use Illuminate\Support\Facades\DB;

class BannerLivewire extends BaseLivewireComponent
{

    //
    public $model = Banner::class;

    //
    public $link;
    public $vendor_id;
    public $category_id;
    public $product_id;
    public $isActive;
    public $featured;
    public $type;
    public $types = ["link", "category", "vendor", "product"];
    public $vendorTypes = [];
    public $vendor_type_id;


    //getlisteners
    public function getListeners()
    {
        return $this->listeners + [
            "vendor_idUpdated" => "autocompleteVendorSelected",
            "product_idUpdated" => "autocompleteProductSelected",
            "category_idUpdated" => "autocompleteCategorySelected",
        ];
    }


    public function render()
    {
        return view('livewire.banners');
    }


    //
    public function fetchVendorTypes()
    {
        $mVendorTypes = VendorType::active()->get();
        //prepend the first option
        $topOptions = [];
        $topOptions[] = [
            "id" => "",
            "name" => __("Home"),
        ];
        $this->vendorTypes = $mVendorTypes->toArray();
        $this->vendorTypes = array_merge($topOptions, $this->vendorTypes);
    }

    public function showCreateModal()
    {
        $this->fetchVendorTypes();
        parent::showCreateModal();
    }

    public function showEditModal()
    {
        $this->fetchVendorTypes();
        parent::showEditModal();
    }


    //select actions
    public function autocompleteVendorSelected($selection)
    {
        $this->vendor_id = $selection["value"];
    }
    public function autocompleteProductSelected($selection)
    {
        $this->product_id = $selection["value"];
    }
    public function autocompleteCategorySelected($selection)
    {
        $this->category_id = $selection["value"];
    }

    //on type change rest the _id fields
    public function updatedType($value)
    {
        $this->reset(["vendor_id", "category_id", "product_id"]);
    }


    public function save()
    {
        //validate
        $this->validate(
            [
                //required type
                "type" => "required",
                //depending on the type: category
                "category_id" => "required_if:type,category|nullable|exists:categories,id",
                //depending on the type: vendor
                "vendor_id" => "required_if:type,vendor|nullable|exists:vendors,id",
                //depending on the type: link
                "link" => "required_if:type,link|nullable|url",
                //depending on the type: product
                "product_id" => "required_if:type,product|nullable|exists:products,id",
                "photo" => "required|image|max:" . setting("filelimit.banner", 2048) . "",

                /*
                "category_id" => "required_without_all:link,vendor_id|nullable|exists:categories,id",
                "link" => "required_without_all:category_id,vendor_id|nullable|url",
                "vendor_id" => "required_without_all:link,category_id|nullable|exists:vendors,id",
                "photo" => "required|image|max:" . setting("filelimit.banner", 2048) . "",
                */
            ],
        );

        try {

            DB::beginTransaction();
            $model = new Banner();
            $model->vendor_type_id = $this->vendor_type_id ?? null;
            $model->category_id = $this->category_id ?? null;
            $model->vendor_id = $this->vendor_id ?? null;
            $model->product_id = $this->product_id ?? null;
            $model->link = $this->link ?? '';
            $model->is_active = $this->isActive ?? false;
            $model->featured = $this->featured ?? false;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Banner created successfully!"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Banner creation failed!"));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->vendor_type_id = $this->selectedModel->vendor_type_id;
        $this->category_id = $this->selectedModel->category_id;
        $this->product_id = $this->selectedModel->product_id;
        $this->vendor_id = $this->selectedModel->vendor_id;
        $this->link = $this->selectedModel->link;
        $this->isActive = $this->selectedModel->is_active;
        $this->featured = $this->selectedModel->featured;
        //update selects
        if ($this->vendor_id != null) {
            $this->emit("vendor_id_Loaded", $this->vendor_id);
        }
        if ($this->product_id != null) {
            $this->emit("product_id_Loaded", $this->product_id);
        }
        if ($this->category_id != null) {
            $this->emit("category_id_Loaded", $this->category_id);
        }
        //
        $this->type = $this->selectedModel->category_id ? "category" : ($this->selectedModel->vendor_id ? "vendor" : ($this->selectedModel->product_id ? "product" : "link"));
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                //required type
                "type" => "required",
                //depending on the type: category
                "category_id" => "required_if:type,category|nullable|exists:categories,id",
                //depending on the type: vendor
                "vendor_id" => "required_if:type,vendor|nullable|exists:vendors,id",
                //depending on the type: link
                "link" => "required_if:type,link|nullable|url",
                //depending on the type: product
                "product_id" => "required_if:type,product|nullable|exists:products,id",
                //photo
                "photo" => "sometimes|nullable|image|max:" . setting("filelimit.banner", 2048) . "",
                // "category_id" => "required_without_all:link,vendor_id|nullable|exists:categories,id",
                // "link" => "required_without_all:category_id,vendor_id|nullable|url",
                // "vendor_id" => "required_without_all:link,category_id|nullable|exists:vendors,id",
                // "photo" => "sometimes|nullable|image|max:" . setting("filelimit.banner", 2048) . "",
            ]
        );

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->vendor_type_id = $this->vendor_type_id ?? null;
            $model->category_id = $this->category_id ?? null;
            $model->vendor_id = $this->vendor_id ?? null;
            $model->product_id = $this->product_id ?? null;
            $model->link = $this->link ?? '';
            $model->is_active = $this->isActive ?? false;
            $model->featured = $this->featured;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Banner updated successfully!"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Banner updated failed!"));
        }
    }
}