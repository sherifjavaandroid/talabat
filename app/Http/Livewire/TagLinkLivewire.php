<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;

class TagLinkLivewire extends BaseLivewireComponent
{

    //
    public $model = Tag::class;


    public function mount($id)
    {
        $this->selectedModel = Tag::find($id);
    }

    public function render()
    {
        return view('livewire.tag_link');
    }
}
