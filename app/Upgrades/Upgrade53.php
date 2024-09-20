<?php

namespace App\Upgrades;



class Upgrade53 extends BaseUpgrade
{

    public $versionName = "1.6.62";
    //Runs or migrations to be done on this version
    public function run()
    {

        //add permissions to nav_menus table if not exists
        if (!\Schema::hasColumn('nav_menus', 'permissions')) {
            \Schema::table('nav_menus', function ($table) {
                $table->string('permissions')->nullable();
            });
        }

        //create spaite permission assign-permissions if not exists and assign to admin
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'assign-permissions']);
        $role = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }
}
