<?php

namespace App\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;
use Illuminate\Support\Str;
use Spatie\Translatable\Events\TranslationHasBeenSetEvent;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

trait HasTranslations
{
    use BaseHasTranslations;

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, $this->getLocale());
        }
        return $attributes;
    }


    // this is to fix the issue with arabic characters
    public function setTranslation(string $key, string $locale, $value): self
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        $translations = $this->getTranslations($key);

        $oldValue = $translations[$locale] ?? '';

        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';

            $this->{$method}($value, $locale);

            $value = $this->attributes[$key];
        }

        //value replace " with \" to fix the issue with arabic characters
        $value = str_replace('"', "\"", $value);
        $translations[$locale] = $value;
        $jsonValue = json_encode($translations, JSON_UNESCAPED_UNICODE);
        $this->attributes[$key] = $jsonValue;

        event(new TranslationHasBeenSetEvent($this, $key, $locale, $oldValue, $value));

        return $this;
    }
}
