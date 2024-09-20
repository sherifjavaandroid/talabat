<?php

namespace App\Models;

use App\Traits\HasTranslations;

class Onboarding extends NoDeleteBaseModel
{

    use HasTranslations;
    public $translatable = ['title', 'description'];
}
