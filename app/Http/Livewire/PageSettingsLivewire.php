<?php

namespace App\Http\Livewire;



class PageSettingsLivewire extends BaseLivewireComponent
{

    //set listeners to emtpy
    protected $listeners = [];

    public function render()
    {
        return view('livewire.settings.pages');
    }
}
