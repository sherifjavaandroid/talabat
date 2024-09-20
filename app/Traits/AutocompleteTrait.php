<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;



trait AutocompleteTrait
{


    //AUTOCOMPLETE FOR VENDOR & PRODUCTS
    public $productIDS;
    public $productSearchClause = ['vendor_id' => 0];
    public $selectedProducts;
    //
    public $itemIDS;
    public $itemSearchClause = ['vendor_id' => 0];
    public $selectedItems;
    //
    public $categoriesIDs;
    public $selectedCategories;
    //
    public $vendorIDS;
    public $vendorSearchClause = ['creator_id' => 0];
    public $selectedVendors;
    //products
    public function autocompleteProductSelected($product)
    {
        try {

            if (count($this->productIDS ?? []) < 1) {
                $this->productIDS = [];
            }

            // 
            $productId = $product['id'];
            $newProductIDs = $this->productIDS;
            if (!is_array($newProductIDs)) {
                $newProductIDs = $newProductIDs->toarray();
            }
            //if product already exists
            if (!in_array($productId, $newProductIDs)) {
                array_push($newProductIDs, $productId);
            }
            $this->productIDS = $newProductIDs;
            $this->selectedProducts = Product::whereIn('id', $this->productIDS)->get();
            //
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    //
    public function removeSelectedProduct($id)
    {
        $this->selectedProducts = $this->selectedProducts->reject(function ($element) use ($id) {
            return $element->id == $id;
        });

        //
        $this->productIDS = $this->selectedProducts->pluck('id') ?? [];
    }

    //vendors
    public function autocompleteVendorSelected($vendor)
    {
        try {

            if (count($this->vendorIDS ?? []) < 1) {
                $this->vendorIDS = [];
            }

            // 
            $vendorId = $vendor['id'];
            $newVendorIDs = $this->vendorIDS;
            if (!is_array($newVendorIDs)) {
                $newVendorIDs = $newVendorIDs->toarray();
            }
            //if product already exists
            if (!in_array($vendorId, $newVendorIDs)) {
                array_push($newVendorIDs, $vendorId);
            }
            $this->vendorIDS = $newVendorIDs;
            $this->selectedVendors = Vendor::whereIn('id', $this->vendorIDS)->get();
            //
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    //
    public function removeSelectedVendor($id)
    {
        $this->selectedVendors = $this->selectedVendors->reject(function ($element) use ($id) {
            return $element->id == $id;
        });

        //
        $this->vendorIDS = $this->selectedVendors->pluck('id') ?? [];
    }


    //products
    public function autocompleteCategorySelected($item)
    {
        try {

            if (count($this->categoriesIDs ?? []) < 1) {
                $this->categoriesIDs = [];
            }

            // 
            $itemId = $item['id'];
            $newItemsIds = $this->categoriesIDs;
            if (!is_array($newItemsIds)) {
                $newItemsIds = $newItemsIds->toarray();
            }
            //if product already exists
            if (!in_array($itemId, $newItemsIds)) {
                array_push($newItemsIds, $itemId);
            }
            $this->categoriesIDs = $newItemsIds;
            $this->selectedCategories = $this->selectedItems = Category::whereIn('id', $this->categoriesIDs)->get();
            //
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    //
    public function removeSelectedCategory($id)
    {
        $this->selectedCategories = $this->selectedCategories->reject(function ($element) use ($id) {
            return $element->id == $id;
        });

        //
        $this->categoriesIDs = $this->selectedCategories->pluck('id') ?? [];
    }
}
