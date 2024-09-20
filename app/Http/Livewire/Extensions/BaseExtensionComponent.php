<?php

namespace App\Http\Livewire\Extensions;

use Livewire\Component;

class BaseExtensionComponent extends Component
{
    public $showView = false;

    public function show(){
        $this->showView = true;
        $this->emitUp('hideExtensions');
    }

    public function hide(){
        $this->showView = false;
    }
}
