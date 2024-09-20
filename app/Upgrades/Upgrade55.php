<?php

namespace App\Upgrades;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Upgrade55 extends BaseUpgrade
{

    public $versionName = "1.6.63";
    //Runs or migrations to be done on this version
    public function run()
    {
        //add new permission: assign-permissions
        //using spatie/laravel-permission
        $permission = Permission::firstorcreate(['name' => "assign-permissions"]);
        $role = Role::where('name', 'admin')->first();
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }
}
