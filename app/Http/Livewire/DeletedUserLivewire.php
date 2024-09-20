<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DeletedUserLivewire extends BaseLivewireComponent
{


    public function render()
    {
        return view('livewire.deleted_users');
    }

}
