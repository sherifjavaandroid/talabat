<?php

namespace App\Http\Livewire;

use App\Models\Option;
use App\Models\OptionGroup;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class OptionLivewire extends BaseLivewireComponent
{

    //
    public $model = Option::class;

    //
    public $name;
    public $description;
    public $price;
    public $option_group_id;
    public $isActive = 1;
    public $productIDS;
    public $product_id;
    


    protected $rules = [
        "name" => "required|string",
        "option_group_id" => "required|exists:option_groups,id",
        "photo" => "sometimes|nullable|image|max:1024",
    ];


    public function mount()
    {
        //
        if (\Auth::user()->hasRole('manager')) {
            $this->productSearchClause = [
                "vendor_id" => \Auth::user()->vendor_id
            ];
        }
    }

    public function render()
    {

        return view('livewire.options', [
            "optionGroups" => $this->getOptionGroup(),
        ]);
    }


    public function getOptionGroup()
    {
        if (User::find(Auth::id())->hasRole('admin')) {
            return OptionGroup::active()->get();
        } else {
            return OptionGroup::active()->where('vendor_id', Auth::user()->vendor_id)->get();
        }
    }


    public function showCreateModal()
    {

        if(\Auth::user()->hasAnyRole('manager')){
            $this->showCreate = true;
            $this->option_group_id = $this->getOptionGroup()->first()->id ?? null;
            $this->showSelect2("#productsSelect2", $this->productIDS, "productsChange");
        }else{
            $this->showWarningAlert(__("Only vendor manager can create new record"));
        }
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new Option();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->price = $this->price ?? 0;
            $model->option_group_id = $this->option_group_id;
            $model->is_active = $this->isActive;
            $model->vendor_id = Auth::user()->vendor_id;
            $model->save();
            //
            $model->products()->sync($this->productIDS);

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Option") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Option") . " " . __('creation failed!'));
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->price = $this->selectedModel->price;
        $this->isActive = $this->selectedModel->is_active;
        $this->product_id = $this->selectedModel->product_id;
        $this->option_group_id = $this->selectedModel->option_group_id;
        $this->productIDS = $this->selectedModel->products->pluck('id');
        $this->selectedProducts = Product::whereIn('id', $this->productIDS)->get();
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
            $model->description = $this->description;
            $model->price = $this->price ?? 0;
            $model->option_group_id = $this->option_group_id;
            $model->is_active = $this->isActive;
            $model->save();

            //
            $model->products()->sync($this->productIDS);

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Option") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Option") . " " . __('updated failed!'));
        }
    }



    //
    public function productsChange($data)
    {
        $this->productIDS = $data;
    }

   
}
