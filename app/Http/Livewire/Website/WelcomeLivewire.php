<?php

namespace App\Http\Livewire\Website;


class WelcomeLivewire extends WebsiteBaseLivewireComponent
{

    public function render()
    {
        return view('livewire.website.welcome')->layout('layouts.website');
    }
}
