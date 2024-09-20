<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Exception;

class CurrencyLivewire extends BaseLivewireComponent
{

    //
    public $model = Currency::class;
    public $name;
    public $code;
    public $country_code;
    public $symbol;

    public function render()
    {
        return view('livewire.settings.currency');
    }



    public function showCreateModal()
    {
        $this->reset();
        $this->dismissModal();
        //clear previous validation errors
        $this->resetErrorBag();
        $this->showCreate = true;
    }

    public function save()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "symbol" => "required|string",
                "country_code" => "required|alpha:ascii|max:2|min:2",
                "code" => "required|alpha:ascii|unique:currencies|max:3|min:3",
            ]
        );


        try {

            $this->isDemo();
            DB::beginTransaction();
            $this->selectedModel = new Currency();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->code = $this->code;
            $this->selectedModel->symbol = $this->symbol;
            $this->selectedModel->country_code = $this->country_code;
            $this->selectedModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Currency") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Currency") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->code = $this->selectedModel->code;
        $this->country_code = $this->selectedModel->country_code;
        $this->symbol = $this->selectedModel->symbol;
        //clear previous validation errors
        $this->resetErrorBag();
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "symbol" => "required|string",
                "country_code" => "required|alpha:ascii|max:2|min:2",
                "code" => "required|alpha:ascii|unique:currencies,code," . $this->selectedModel->id . "|max:3|min:3",
            ]
        );

        try {

            $this->isDemo();
            DB::beginTransaction();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->code = $this->code;
            $this->selectedModel->country_code = $this->country_code;
            $this->selectedModel->symbol = $this->symbol;
            $this->selectedModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Currency") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Currency") . " " . __('updated failed!'));
        }
    }
}
