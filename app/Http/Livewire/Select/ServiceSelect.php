<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Service;

class ServiceSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {

        $vendorId = $this->getDependingValue('vendor_id') ?? \Auth::user()->vendor_id ?? "";

        //
        return Service::where('name', 'like', '%' . $searchTerm . '%')
            ->where('vendor_id', $vendorId)
            ->limit(10)
            ->get()
            ->map(function ($service) {
                return [
                    'value' => $service->id,
                    'description' => $service->name,
                ];
            });
    }


    public function selectedOption($value)
    {
        $model = Service::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name
        ];
    }
}
