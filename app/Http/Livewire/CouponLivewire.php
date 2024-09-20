<?php

namespace App\Http\Livewire;


use App\Models\Vendor;
use App\Models\Product;
use App\Models\VendorType;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class CouponLivewire extends BaseLivewireComponent
{

    //
    public $model = Coupon::class;

    //
    public $code;
    public $color;
    public $description;
    public $discount;
    public $percentage;
    public $expires_on;
    public $times;
    public $min_order_amount;
    public $max_coupon_amount;
    public $isActive = 1;
    public $for_delivery = 0;
    public $productIDS;
    public $vendorsIDS;
    public $vendor_type_id;
    public $vendorTypes;

    //
    public $customQuery;

    protected $rules = [
        "code" => "required|string|unique:coupons",
        "discount" => "required|numeric",
        "expires_on" => "required|date",
        "photo" => "sometimes|image|max:1024",
    ];



    //
    public function mount()
    {
        //
        if (\Auth::user()->hasRole('manager')) {
            $this->productSearchClause = [
                "vendor_id" => \Auth::user()->vendor_id
            ];
            //
            $this->vendorSearchClause = ['id' =>  \Auth::user()->vendor_id];
        } else if (\Auth::user()->hasRole('city-admin')) {
            $this->customQuery = "city-admin-products";
            $this->vendorSearchClause = ['creator_id' =>  \Auth::user()->id];
        } else {
            $this->productSearchClause = [];
            $this->vendorSearchClause = [];
        }
    }

    public function render()
    {
        if (empty($this->vendorTypes)) {
            $this->vendorTypes = VendorType::active()->get();
        }
        return view('livewire.coupons');
    }




    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new Coupon();
            $model->code = $this->code;
            $model->color = $this->color ?? "#000000";
            $model->description = $this->description;
            $model->discount = $this->discount;
            $model->min_order_amount = $this->min_order_amount;
            $model->max_coupon_amount = $this->max_coupon_amount;
            $model->percentage = $this->percentage ?? false;
            $model->expires_on = $this->expires_on;
            $model->times = $this->times;
            $model->is_active = $this->isActive;
            $model->for_delivery = $this->for_delivery;
            if (\Schema::hasColumn('coupons', 'vendor_type_id')) {
                $model->vendor_type_id = $this->vendor_type_id;
            }

            $model->creator_id = \Auth::id();
            $model->save();

            if ($this->photo) {
                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            $model->products()->attach($this->productIDS);
            $model->vendors()->attach($this->vendorsIDS);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Coupon") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Coupon") . " " . __('creation failed!'));
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->code = $this->selectedModel->code;
        $this->color = $this->selectedModel->color;
        $this->description = $this->selectedModel->description;
        $this->discount = $this->selectedModel->discount;
        $this->min_order_amount = $this->selectedModel->min_order_amount;
        $this->max_coupon_amount = $this->selectedModel->max_coupon_amount;
        $this->percentage = $this->selectedModel->percentage;
        $this->expires_on = $this->selectedModel->expires_on;
        $this->times = $this->selectedModel->times;
        $this->isActive = $this->selectedModel->is_active;
        $this->for_delivery = $this->selectedModel->for_delivery;
        $this->vendor_type_id = $this->selectedModel->vendor_type_id;

        $this->productIDS = $this->selectedModel->products()->pluck('id');
        $this->selectedProducts = Product::whereIn('id', $this->productIDS)->get();
        $this->vendorIDS = $this->selectedModel->vendors()->pluck('id');
        $this->selectedVendors = Vendor::whereIn('id', $this->vendorIDS)->get();

        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "code" => "required|string|unique:coupons,code," . $this->selectedModel->id . "",
                "discount" => "required|numeric",
                "expires_on" => "required|date",
            ]
        );

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->code = $this->code;
            $model->color = $this->color ?? "#000000";
            $model->description = $this->description;
            $model->discount = $this->discount;
            $model->percentage = $this->percentage;
            $model->expires_on = $this->expires_on;
            $model->times = $this->times;
            $model->min_order_amount = $this->min_order_amount;
            $model->max_coupon_amount = $this->max_coupon_amount;
            $model->is_active = $this->isActive;
            $model->for_delivery = $this->for_delivery;
            if (\Schema::hasColumn('coupons', 'vendor_type_id')) {
                $model->vendor_type_id = $this->vendor_type_id;
            }
            $model->save();

            if ($this->photo) {
                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            $model->products()->sync($this->productIDS);
            $model->vendors()->sync($this->vendorIDS);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Coupon") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Coupon") . " " . __('updated failed!'));
        }
    }



    public function productsChange($data)
    {
        $this->productsIDs = $data;
    }

    public function vendorsChange($data)
    {
        $this->vendorsIDs = $data;
    }
}
