<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;

class PaymentMethodivewire extends BaseLivewireComponent
{

    //
    public $model = PaymentMethod::class;

    //
    public $name;
    public $secret_key;
    public $public_key;
    public $hash_key;
    public $instruction;
    public $isActive;
    public $allow_pickup;
    //min_order & max_order
    public $min_order = 0;
    public $max_order;

    protected $rules = [
        "name" => "required|string",
        //if min_order is provided, then must be a number and greater than 0
        "min_order" => "nullable|numeric|min:0",
        //if max_order is provided, then must be a number and greater than 0 and greater than min_order
        "max_order" => "nullable|numeric|min:0",
    ];


    public function render()
    {
        return view('livewire.payment-methods');
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->secret_key = $this->selectedModel->secret_key;
        $this->public_key = $this->selectedModel->public_key;
        $this->hash_key = $this->selectedModel->hash_key;
        $this->instruction = $this->selectedModel->instruction;
        $this->isActive = $this->selectedModel->is_active;
        $this->allow_pickup = $this->selectedModel->allow_pickup;
        $this->min_order = $this->selectedModel->min_order;
        $this->max_order = $this->selectedModel->max_order;
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
            $model->secret_key = $this->secret_key;
            $model->public_key = $this->public_key;
            $model->hash_key = $this->hash_key;
            $model->instruction = $this->instruction;
            $model->is_active = $this->isActive;
            $model->allow_pickup = $this->allow_pickup;
            $model->min_order = $this->min_order;
            $model->max_order = $this->max_order;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }


            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Payment Method") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Payment Method") . " " . __('creation failed!'));
        }
    }
}