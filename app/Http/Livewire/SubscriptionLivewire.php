<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;

class SubscriptionLivewire extends BaseLivewireComponent
{

    //
    public $model = Subscription::class;

    //
    public $name;
    public $days;
    public $qty;
    public $amount;
    public $isActive;

    protected $rules = [
        "name" => "required|string",
        "days" => "required|numeric",
        "qty" => "sometimes|numeric",
        "amount" => "required|numeric",
    ];


    public function render()
    {
        return view('livewire.subscriptions');
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->days = $this->selectedModel->days;
        $this->qty = $this->selectedModel->qty;
        $this->amount = $this->selectedModel->amount;
        $this->isActive = $this->selectedModel->is_active;
        $this->emit('showEditModal');
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = new Subscription();
            $model->name = $this->name;
            $model->days = $this->days;
            $model->qty = $this->qty;
            $model->amount = $this->amount;
            $model->is_active = $this->isActive;
            $model->save();
         
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Subscription") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Subscription") . " " . __('creation failed!'));
        }
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
            $model->days = $this->days;
            $model->qty = $this->qty;
            $model->amount = $this->amount;
            $model->is_active = $this->isActive;
            $model->save();
         
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Subscription") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Subscription") . " " . __('update failed!'));
        }
    }
}


