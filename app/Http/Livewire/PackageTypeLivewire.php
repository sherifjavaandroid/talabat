<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\PackageType;

class PackageTypeLivewire extends BaseLivewireComponent
{

    //
    public $model = PackageType::class;

    //
    public $name;
    public $description;
    public $driver_verify_stops;
    public $is_active;

    protected $rules = [
        "name" => "required|string",
    ];


    public function render()
    {
        return view('livewire.package_types');
    }

    public function save(){
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.package_type", 300) . "";
        $this->validate($rules);

        try{
            $this->isDemo();
            DB::beginTransaction();
            $model = new PackageType();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->driver_verify_stops = $this->driver_verify_stops ?? false;
            $model->is_active = $this->is_active ?? 0;
            $model->save();

            if( $this->photo ){

                $model->clearMediaCollection();
                $model->addMedia( $this->photo->getRealPath() )->toMediaCollection();
                $this->photo = null;

            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Package Type")." ".__('created successfully!'));
            $this->emit('refreshTable');


        }catch(Exception $error){
            DB::rollback();
            $this->showErrorAlert( $error->getMessage() ?? __("Package Type")." ".__('creation failed!'));
        }

    }

    public function initiateEdit($id){
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->driver_verify_stops = $this->selectedModel->driver_verify_stops;
        $this->is_active = $this->selectedModel->is_active;
        $this->emit('showEditModal');
    }

    public function update(){
        //validate
        $rules = $this->rules;
        $rules["photo"] = "nullable|sometimes|image|max:" . setting("filelimit.package_type", 300) . "";
        $this->validate($rules);

        try{
            $this->isDemo();
            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->description = $this->description;
            $model->driver_verify_stops = $this->driver_verify_stops;
            $model->is_active = $this->is_active ?? 0;
            $model->save();

            if( $this->photo ){

                $model->clearMediaCollection();
                $model->addMedia( $this->photo->getRealPath() )->toMediaCollection();
                $this->photo = null;

            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Package Type")." ".__('updated successfully!'));
            $this->emit('refreshTable');


        }catch(Exception $error){
            DB::rollback();
            $this->showErrorAlert( $error->getMessage() ?? __("Package Type")." ".__('updated failed!'));

        }

    }



}
