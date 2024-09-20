<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class CancellationReason extends Model
{
    use HasFactory;
    use HasTranslations;
    public $translatable = ['reason'];
}
