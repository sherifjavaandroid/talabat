<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\User;

class UserSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {
        return User::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email', 'like', '%' . $searchTerm . '%')
            ->orWhere('phone', 'like', '%' . $searchTerm . '%')
            ->limit(20)
            ->get()
            ->map(function ($model) {
                return [
                    'value' => $model->id,
                    'description' => $model->name . " - " . $model->email,
                ];
            });
    }


    public function selectedOption($value)
    {
        return [
            'value' => $value,
            'description' =>  User::find($value)->name,
        ];
    }
}
