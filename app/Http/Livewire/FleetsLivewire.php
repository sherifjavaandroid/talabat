<?php

namespace App\Http\Livewire;

use App\Models\Fleet;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class FleetsLivewire extends BaseLivewireComponent
{

    //
    public $model = Fleet::class;
    public $selectRole;

    //
    public $name;
    public $email;
    public $phone;
    public $address;
    //
    public $managersIds = [];
    public $fleetManagers = [];

    protected $rules = [
        "name" => "required|string",
        "email" => "nullable|sometimes|string",
        "phone" => "nullable|sometimes|string",
        "address" => "nullable|sometimes|string",
    ];



    public function render()
    {
        return view('livewire.fleets');
    }



    public function save()
    {
        //validate
        $data = $this->validate();

        try {

            DB::beginTransaction();
            $fleet = new Fleet();
            $fleet->fill($data);
            $fleet->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Fleet") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("Fleet Create Error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Fleet") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        try {

            $this->isDemo();
            $this->reset();
            $this->selectedModel = $this->model::find($id);
            $this->name = $this->selectedModel->name;
            $this->email = $this->selectedModel->email;
            $this->phone = $this->selectedModel->phone;
            $this->address = $this->selectedModel->address;
            $this->emit('showEditModal');
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Fleet") . " " . __('failed!'));
        }
    }

    public function update()
    {
        //validate
        $data = $this->validate();

        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->selectedModel->update($data);


            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Fleet") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Fleet") . " " . __('updated failed!'));
        }
    }


    // Assigning vendors
    public function initiateAssign($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->managersIds = $this->selectedModel->managers->pluck('id');
        $this->fleetManagers = User::active()->role('fleet-manager')->get();
        $this->emit('showAssignModal');
        $this->showSelect2("#managersSelect2", $this->managersIds, "managersChange", $this->fleetManagers);
    }

    public function managersChange($managers)
    {
        $this->managersIds = $managers;
    }

    public function assignManagers()
    {
        try {

            DB::beginTransaction();
            $this->selectedModel->managers()->sync($this->managersIds);
            DB::commit();
            $this->emit('dismissModal');
            $this->showSuccessAlert(__("Fleet manager assigned successfully"));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Fleet manager assignment failed"));
        }
    }

    public function vendorsChange($data)
    {
        $this->vendorsIDs = $data;
    }
}
