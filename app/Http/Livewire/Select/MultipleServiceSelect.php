<?php

namespace App\Http\Livewire\Select;


use Illuminate\Support\Collection;
use App\Models\Service;

class MultipleServiceSelect extends BaseLivewireSelect
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
        if ($value != null) {
            $this->selectValue(null);
            $this->searchTerm = null;
        }
        return [
            'value' =>  "",
            'description' => "",
        ];
    }
}
