<?php

namespace App\Http\Livewire\FlashSale;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\VendorType;
use Maatwebsite\Excel\Concerns\ToArray;

class FlashSaleLivewire extends BaseLivewireComponent
{


    //
    public $vendor_type_id;
    public $title;
    public $expires_at;
    public $is_active;
    public $vendorTypes = [];
    public $search_input;
    public $searchResult = [];
    public $selectedProducts = [];
    public $selectedProductIds = [];

    public function getListeners()
    {
        return $this->listeners + [
            "productSelected" => "productSelected",
            'product_idUpdated' => "productIdUpdated",
            'vendor_type_idUpdated' => "vendorTypeIdUpdated",
        ];
    }

    public function render()
    {
        return view('livewire.flash_sales');
    }


    public function updatedVendorTypeId($keyword)
    {
        $this->searchResult = [];
        $this->selectedProductIds = [];
        $this->selectedProducts = [];
    }

    public function updatedSearchInput($keyword)
    {
        $this->searchResult = Product::select('id', 'name', 'vendor_id')
            ->setEagerLoads([])->where("name", "like", $keyword . "%")->whereHas('vendor', function ($query) {
                return $query->where('vendor_type_id', $this->vendor_type_id);
            })->limit($this->perPage)->get();
    }

    public function productSelected($key)
    {
        array_push($this->selectedProductIds, $this->searchResult[$key]->id);
        $this->selectedProductIds = array_unique($this->selectedProductIds);
        $this->selectedProducts = Product::whereIn('id', $this->selectedProductIds)->get();
    }

    public function vendorTypeIdUpdated($value)
    {
        $mVendorTypeId = $value['value'];

        if ($mVendorTypeId == $this->vendor_type_id) {
            return;
        }

        $this->vendor_type_id = $mVendorTypeId;
        $this->searchResult = [];
        $this->selectedProductIds = [];
        $this->selectedProducts = [];
    }

    public function productIdUpdated($value)
    {
        $id = $value['value'];
        if ($id == null) {
            return;
        }
        array_push($this->selectedProductIds, $id);
        $this->selectedProductIds = array_unique($this->selectedProductIds);
        $this->selectedProducts = Product::whereIn('id', $this->selectedProductIds)->get();
    }

    public function removeItem($key)
    {
        unset($this->selectedProductIds[$key]);
        sort($this->selectedProductIds);
        $this->selectedProducts = Product::whereIn('id', $this->selectedProductIds)->get();
    }


    public function showCreateModal()
    {
        $this->showCreate = true;
        $this->stopRefresh = true;
        $this->emit('initial_vendor_type', null);
    }

    public function save()
    {

        $this->validate(
            [
                "vendor_type_id" => "required",
                "title" => "required",
                "expires_at" => "required|date",
            ]
        );

        //
        if (empty($this->selectedProducts)) {
            $this->showWarningAlert(__("Please select at least one(1) item/product"));
            return;
        }

        try {
            $this->isDemo();
            \DB::beginTransaction();
            $model = new FlashSale();
            $model->name = $this->title;
            $model->expires_at = $this->expires_at;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->is_active = $this->is_active;
            $model->save();

            //
            foreach ($this->selectedProducts as  $product) {
                $flashSaleItem = new FlashSaleItem();
                $flashSaleItem->flash_sale_id = $model->id;
                $flashSaleItem->item()->associate($product)->save();
                $flashSaleItem->save();
            }
            \DB::commit();
            $this->showSuccessAlert(__("Flash sale") . " " . __('created successfully!'));
            $this->dismissModal();
            $this->reset();
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Flash sale") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = FlashSale::find($id);
        $this->vendor_type_id = $this->selectedModel->vendor_type_id;
        $this->emit('initial_vendor_type', $this->vendor_type_id);
        //
        $this->selectedProductIds = $this->selectedModel->items->pluck("item_id")->toArray();
        $this->selectedProducts = Product::whereIn('id', $this->selectedProductIds)->get();
        $this->title = $this->selectedModel->name;
        $this->expires_at = $this->selectedModel->expires_at;
        $this->is_active = $this->selectedModel->is_active;
        $this->showEditModal();
    }

    public function update()
    {

        $this->validate(
            [
                "vendor_type_id" => "required",
                "title" => "required",
                "expires_at" => "required|date",
            ]
        );

        //
        if (empty($this->selectedProducts)) {
            $this->showWarningAlert(__("Please select at least one(1) item/product"));
            return;
        }

        try {
            $this->isDemo();
            \DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->title;
            $model->expires_at = $this->expires_at;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->is_active = $this->is_active;
            $model->save();

            FlashSaleItem::where('flash_sale_id', $model->id)->delete();

            //
            foreach ($this->selectedProducts as  $product) {
                $flashSaleItem = new FlashSaleItem();
                $flashSaleItem->flash_sale_id = $model->id;
                $flashSaleItem->item()->associate($product)->save();
                $flashSaleItem->save();
            }
            \DB::commit();
            $this->showSuccessAlert(__("Flash sale") . " " . __('updated successfully!'));
            $this->dismissModal();
            $this->reset();
            $this->emit('refreshTable');
        } catch (\Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Flash sale") . " " . __('updated failed!'));
        }
    }
}
