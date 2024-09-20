<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\DeliveryAddress;


class NewOrderDeliveryAddressSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        $userId = $this->getDependingValue('user_id') ?? 0;
        return DeliveryAddress::where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orwhere('address', 'like', '%' . $searchTerm . '%');
        })
            ->where('user_id', $userId)
            ->limit(20)
            ->get()
            ->map(function ($model) {
                return [
                    'value' => $model->id,
                    'description' => $model->name . ' - ' . $model->address,
                ];
            });
    }


    public function selectedOption($value)
    {
        $model = DeliveryAddress::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name . ' - ' . $model->address,
        ];
    }
}
