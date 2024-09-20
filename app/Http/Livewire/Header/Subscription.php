<?php

namespace App\Http\Livewire\Header;

use App\Models\Vendor;
use Livewire\Component;

class Subscription extends Component
{
    protected $listeners = [
        // 'logout' => 'logout',
        // 'changeFCMToken' => 'changeFCMToken',
    ];

    public $subExpired = false;

    public function mount()
    {
        $vendor = Vendor::find(\Auth::user()->vendor_id);
        if (!empty($vendor)) {
            $this->subExpired =  !$vendor->has_subscription;
        }
    }

    public function render()
    {
        return view('livewire.header.subscription');
    }
}
