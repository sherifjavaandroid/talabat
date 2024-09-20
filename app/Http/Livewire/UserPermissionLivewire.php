<?php

namespace App\Http\Livewire;

use App\Models\User;
use Exception;
use Spatie\Permission\Models\Permission;

class UserPermissionLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;
    public $permissions = [];
    public $selectedPermissions = [];
    public $selectedModel;

    public function mount($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->permissions = Permission::all();
        $this->selectedPermissions = $this->selectedModel->permissions->pluck('name')->toArray();
        //get permissions of the roles of the user
        $this->selectedModel->roles->each(function ($role) {
            $this->selectedPermissions = array_merge($this->selectedPermissions, $role->permissions->pluck('name')->toArray());
        });
    }

    public function render()
    {
        return view('livewire.user_permissions');
    }

    public function save()
    {
        try {
            $this->isDemo();
            $this->selectedModel->syncPermissions($this->selectedPermissions);
            $this->showSuccessAlert(__("User") . " " . __('permissions updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("User") . " " . __('permissions update failed!'));
        }
    }
}
