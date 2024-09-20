<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class DocumentRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'id' => 'integer',
        'model_id' => 'integer',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }


    //
    public function getDocumentsAttribute()
    {
        return $this->getMedia('documents');
    }
}
