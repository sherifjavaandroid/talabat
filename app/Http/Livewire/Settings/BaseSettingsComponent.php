<?php

namespace App\Http\Livewire\Settings;

use App\Http\Livewire\BaseLivewireComponent;

class BaseSettingsComponent extends BaseLivewireComponent
{

    

    //
    function fileEditContents($file_name, $line, $new_value)
    {
        $file = explode("\n", rtrim(file_get_contents($file_name)));
        $file[$line] = $new_value;
        $file = implode("\n", $file);
        file_put_contents($file_name, $file);
    }

    public function goback(){
        $this->emitUp("goBack");
    }

}
