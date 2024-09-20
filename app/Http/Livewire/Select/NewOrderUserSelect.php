<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\User;

class NewOrderUserSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {
        return User::where('name', 'like', '%' . $searchTerm . '%')
            ->limit(10)
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
