<?php

namespace App\Http\Livewire;

use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\ServiceOptionGroup;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceOptionLivewire extends BaseLivewireComponent
{


    //
    public $model = ServiceOption::class;

    //
    public $name;
    public $description;
    public $price;
    public $option_group_id;
    public $isActive = 1;

    //
    public $servicesIDS;
    public $selectedServices;



    protected $rules = [
        "name" => "required|string",
        "option_group_id" => "required|exists:service_option_groups,id",
        "photo" => "sometimes|nullable|image|max:1024",
    ];

    public function getListeners()
    {
        return $this->listeners + [
            'service_idUpdated' => "serviceSelected",
        ];
    }


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

        return view('livewire.service-options', [
            "optionGroups" => $this->getOptionGroup(),
        ]);
    }


    public function getOptionGroup()
    {
        if (User::find(Auth::id())->hasRole('admin')) {
            return ServiceOptionGroup::active()->get();
        } else {
            return ServiceOptionGroup::active()->where('vendor_id', Auth::user()->vendor_id)->get();
        }
    }

    public function serviceSelected($value)
    {
        //
        if ($value == null || !$value) {
            return;
        }
        //
        $this->servicesIDS ??= [];
        //if is not array
        if (!is_array($this->servicesIDS)) {
            $this->servicesIDS = [];
        }
        $serviceId = $value['value'];
        //add to array if not already added
        if (!in_array($serviceId, $this->servicesIDS)) {
            $this->servicesIDS[] = $serviceId;
        }
        //
        $this->selectedServices = Service::whereIn('id', $this->servicesIDS)->get();
        //emit to clear selection
        $this->emitUp('service_idUpdated', null);
    }

    public function removeSelectedService($id)
    {
        $this->selectedServices = $this->selectedServices->reject(function ($element) use ($id) {
            return $element->id == $id;
        });

        //
        $this->servicesIDS = $this->selectedServices->pluck('id') ?? [];
    }

    public function showCreateModal()
    {

        if (\Auth::user()->hasAnyRole('manager')) {
            $this->showCreate = true;
            $this->option_group_id = $this->getOptionGroup()->first()->id ?? null;
            $this->showSelect2("#productsSelect2", $this->productIDS, "productsChange");
        } else {
            $this->showWarningAlert(__("Only vendor manager can create new record"));
        }
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new ServiceOption();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->price = $this->price ?? 0;
            $model->service_option_group_id = $this->option_group_id;
            $model->is_active = $this->isActive;
            $model->vendor_id = Auth::user()->vendor_id;
            $model->save();

            //for each service add to pivot table
            foreach ($this->servicesIDS as $serviceId) {
                $model->services()->attach($serviceId);
            }

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
        $this->option_group_id = $this->selectedModel->service_option_group_id;
        $this->servicesIDS = $this->selectedModel->services->pluck('id');
        $this->selectedServices = Service::whereIn('id', $this->servicesIDS)->get();
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
            $model->service_option_group_id = $this->option_group_id;
            $model->is_active = $this->isActive;
            $model->save();

            //delete old option_service using DB
            DB::table('option_service')->where('service_option_id', $model->id)->delete();
            //for each service add to pivot table
            foreach ($this->servicesIDS as $serviceId) {
                $model->services()->attach($serviceId);
            }

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
}
