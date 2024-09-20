<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MultipleMediaUpload extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $title;
    public $name = "photos";
    public $types;
    public $fileTypes;
    public $photos = [];
    public $oldPhotos = [];
    public $image = true;
    public $multiple = true;
    public $onPhotosSelected;
    public $emitFunction;
    public $previewsEmit;
    public $preview = [];
    public $max = 3;
    public $maxSize = 2048;

    protected function getListeners()
    {
        return [
            $this->onPhotosSelected => 'photoSelectionChanged',
            $this->previewsEmit => 'previewsEmit',
        ];
    }

    public function render()
    {

        return view('livewire.component.multiple-media-upload');
    }

    public function removePhoto($key)
    {
        unset($this->photos[$key]);
        $this->photos = array_values($this->photos);
        $this->photoSelectionChanged();
    }

    public function hydrate()
    {
        //
        $this->oldPhotos = $this->photos;
    }

    public function updatedPhotos($value)
    {

        $this->photos = array_merge($this->oldPhotos, $this->photos);

        if (count($this->photos) > $this->max) {
            $this->alert('warning', "", [
                'position'  =>  'center',
                'text' => __("Maximum of") . " " . $this->max . " " . __("is allowed"),
                'toast'  =>  false,
            ]);
            $this->photos = array_slice($this->photos, 0, $this->max);
        }

        $this->photoSelectionChanged();
    }

    public function photoSelectionChanged()
    {
        $photoPaths = [];
        $skipped = false;
        foreach ($this->photos as $photo) {
            //
            $fileSize = $this->getSize($photo);
            if ($this->maxSize < $fileSize) {
                $skipped = true;
                continue;
            }
            $photoPath = $photo->getRealPath();
            array_push($photoPaths, $photoPath);
        }
        $this->emitUp($this->emitFunction, $photoPaths);
        //
        if ($skipped) {
            //
            $this->alert('warning', "", [
                'position'  =>  'center',
                'text' => __("Some upload have been discarded because of file upload size restriction").".\n"."{$this->maxSize}kilobytes",
                'toast'  =>  false,
            ]);
            $this->photos = [];
        }
    }

    public function previewsEmit($preview)
    {
        $this->preview = $preview;
    }


    public function getSize($photo)
    {
        $filePath = pathinfo($photo->getRealPath());
        $sizeInPath = filesize($filePath['dirname'] . '/' . $filePath['basename']);
        return $sizeInPath / 1000;
    }
}
