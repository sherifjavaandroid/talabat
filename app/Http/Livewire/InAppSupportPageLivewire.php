<?php

namespace App\Http\Livewire;


class InAppSupportPageLivewire extends BaseLivewireComponent
{

    public $inappSupportCode;

    public function mount()
    {
        $this->inappSupportCode = setting("inapp.support", "");
    }

    public function render()
    {
        return view('livewire.inapp_support_page')->layout('layouts.guest');
    }

    public function load()
    {
        if ($this->valid_URL($this->inappSupportCode)) {
            return redirect()->away($this->inappSupportCode);
        }
    }


    function valid_URL($url)
    {
        return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url);
    }
}
