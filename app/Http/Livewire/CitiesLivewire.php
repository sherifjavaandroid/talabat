<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\DB;

class CitiesLivewire extends BaseLivewireComponent
{

    //
    public $model = City::class;

    //
    public $name;
    public $country_id;
    public $state_id;
    public $states = [];
    public $countries = [];

    protected $rules = [
        "name" => "required|string",
        "state_id" => "required|exists:states,id",
    ];


    public function mount()
    {
        $this->countries = Country::get();
        $this->country_id = $this->countries->first()->id ?? null;
        $this->updatedCountryId($this->country_id);
    }

    public function render()
    {

        if (empty($this->countries)) {
            $this->mount();
        }

        return view('livewire.cities');
    }


    public function updatedCountryId($country_id)
    {
        $this->states = State::where('country_id', $country_id)->get();
        $this->state_id = $this->states->first()->id ?? null;
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new City();
            $model->name = $this->name;
            $model->state_id = $this->state_id;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->emit('refreshTable');
            $this->showSuccessAlert(__("City") . " " . __('created successfully!'));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("City") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->state_id = $this->selectedModel->state_id;
        $this->country_id = $this->selectedModel->state->country->id;
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
            $model->state_id = $this->state_id;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->emit('refreshTable');
            $this->showSuccessAlert(__("City") . " " . __('updated successfully!'));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("City") . " " . __('updated failed!'));
        }
    }
}
