<?php

namespace App\Http\Livewire;

use App\Models\DeliveryAddress;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class DeliveryAddressLivewire extends BaseLivewireComponent
{

    public $model = DeliveryAddress::class;
    public $user_id;
    public $users = [];
    public $user;
    public $name;
    public $description;
    public $address;
    public $latitude;
    public $longitude;
    public $city;
    public $state;
    public $country;


    protected $rules = [
        "user_id" => "required|exists:users,id",
        "name" => "required|string",
        "address" => "required|string",
        "latitude" => "required",
        "longitude" => "required",
        "city" => "required|string",
        "state" => "required|string",
        "country" => "required|string",
    ];


    public function render()
    {
        return view('livewire.delivery-addresses');
    }

    public function autocompleteAddressSelected($data)
    {
        $this->address = $data["address"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
        $this->city = $data["city"] ?? '';
        $this->state = $data["state"] ?? '';
        $this->country = $data["country"] ?? '';
    }

    public function updatedUser()
    {
        $this->users = User::where("name", "like", "%" . $this->user . "%")->limit(10)->get();
    }

    public function autocompleteUserSelected($key)
    {
        try {
            $this->user_id = $this->users[$key]->id;
            $this->user = $this->users[$key]->name;
            $this->users = [];
        } catch (\Exception $ex) {
        }
    }


    public function showCreateModal()
    {
        $this->emit('initialAddressSelected', "");
        $this->showCreate = true;
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new DeliveryAddress();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->city = $this->city;
            $model->state = $this->state;
            $model->country = $this->country;
            $model->user_id = $this->user_id;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery address")." ".__('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert( $error->getMessage() ?? __("Delivery address")." ".__('creation failed!'));
        }
    }


    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->address = $this->selectedModel->address;
        $this->latitude = $this->selectedModel->latitude;
        $this->longitude = $this->selectedModel->longitude;
        $this->city = $this->selectedModel->city;
        $this->state = $this->selectedModel->state;
        $this->country = $this->selectedModel->country;
        $this->emit('initialAddressSelected', $this->address);
        $this->emit('showEditModal');
    }

    public function update()
    {
        $validateRules = $this->rules;
        unset($validateRules["user_id"]);
        //validate
        $this->validate($validateRules);

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->description = $this->description;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->city = $this->city;
            $model->state = $this->state;
            $model->country = $this->country;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery address")." ".__('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert( $error->getMessage() ?? __("Delivery address")." ".__('updated failed!'));
        }
    }
}
