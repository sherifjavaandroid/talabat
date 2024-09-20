<?php

namespace App\Http\Livewire\Select;

use Asantibanez\LivewireSelect\LivewireSelect;

class BaseLivewireSelect extends LivewireSelect
{
    //add listener to when the component is loaded
    public function getListeners()
    {
        //get the dependencies from the child class
        return parent::getListeners() + [
            "{$this->name}_Loaded" => "selectValue",
        ];
    }

    public function styles()
    {
        return [
            'default' => 'p-2 rounded border w-full appearance-none',

            'searchSelectedOption' => 'p-2 rounded border w-full bg-white flex items-center',
            'searchSelectedOptionTitle' => 'w-full text-gray-900 text-left',
            'searchSelectedOptionReset' => 'h-4 w-4 text-gray-500',

            'search' => 'relative',
            'searchInput' => 'p-2 rounded border w-full rounded',
            'searchOptionsContainer' => 'absolute top-0 left-0 mt-12 w-full z-10 border bg-white rounded border-t-0',

            'searchOptionItem' => 'px-4 py-2 border-b hover:bg-gray-100 cursor-pointer text-sm',
            'searchOptionItemActive' => 'bg-primary-600 text-white font-medium',
            'searchOptionItemInactive' => 'bg-white text-gray-600',

            'searchNoResults' => 'p-4 w-full bg-white border font-semibold text-center text-sm shadow text-gray-600',
        ];
    }
}
