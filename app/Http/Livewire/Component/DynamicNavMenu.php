<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;
use App\Models\NavMenu;

class DynamicNavMenu extends Component
{

    public $menu = [];

    public function mount()
    {
        if (\Schema::hasTable('nav_menus')) {
            $this->menu = NavMenu::get();
        }
    }

    public function render()
    {
        return view('livewire.component.dynamic-nav-menu');
    }

  
}
