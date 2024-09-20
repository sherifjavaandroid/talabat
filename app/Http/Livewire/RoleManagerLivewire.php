<?php

namespace App\Http\Livewire;

use Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagerLivewire extends BaseLivewireComponent
{

    public $name;
    public $permissions = [];
    public $selectedPermissions = [];
    public $rules = [
        "selectedPermissions.*" => "sometimes"
    ];


    public function getPermissions()
    {

        $this->permissions = Permission::all();
    }

    public function render()
    {
        if (empty($this->permissions)) {
            $this->getPermissions();
        }
        return view('livewire.settings.roles');
    }


    public function save()
    {
        $this->validate(
            [
                "name" => "required"
            ]
        );

        try {
            $this->isDemo();
            \DB::beginTransaction();
            Role::firstorcreate([
                'name' => strtolower($this->name),
            ], [
                'guard_name' => 'web'
            ]);
            \DB::commit();
            $this->showSuccessAlert(__("Role") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Role") . " " . __('created failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = Role::find($id);
        $permissions = $this->selectedModel->permissions()->get();
        if (!$permissions->isEmpty()) {
            $this->selectedPermissions = $permissions->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['name']];
            });
        } else {
            $this->selectedPermissions = [];
        }
        $this->showEditModal();
    }

    public function update()
    {

        $permissions = $this->selectedModel->permissions()->get();

        try {
            $this->isDemo();
            \DB::beginTransaction();
            if (!$permissions->isEmpty()) {
                $this->selectedModel->syncPermissions($this->selectedPermissions);
            } else {
                $this->selectedModel->givePermissionTo($this->selectedPermissions);
            }
            \DB::commit();
            $this->showSuccessAlert(__("Role") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Role") . " " . __('updated failed!'));
        }
    }


    public function dismissModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->stopRefresh = false;
        $this->showAssign = false;
        $this->reset();
    }
}
