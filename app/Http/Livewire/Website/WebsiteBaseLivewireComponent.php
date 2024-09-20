<?php

namespace App\Http\Livewire\Website;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class WebsiteBaseLivewireComponent extends Component
{
    use LivewireAlert;

    protected $listeners = [
        'refreshView' => '$refresh',
        'openNewTab' => 'openNewTab',
    ];


    //Alert
    public function showSuccessAlert($message = "", $time = 3000)
    {
        $this->alert('success', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showWarningAlert($message = "", $time = 3000)
    {
        $this->alert('warning', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showErrorAlert($message = "", $time = 3000)
    {
        $this->alert('error', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }


    public function openNewTab($link)
    {
        return $this->emitUp($link);
    }
}
