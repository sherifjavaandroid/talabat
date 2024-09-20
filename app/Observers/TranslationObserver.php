<?php

namespace App\Observers;


class TranslationObserver
{


    public function created($model)
    {
        $this->handleModelFallbackTranslation($model);
    }


    public function updated($model)
    {
        $this->handleModelFallbackTranslation($model);
    }



    public function handleModelFallbackTranslation($model)
    {
        //get all the keys of the trans array
        $translatable = $model->translatable;
        //if the model is not translatable
        if (empty($translatable)) {
            return;
        }

        //loop through the translatable keys
        foreach ($translatable as $columnName) {
            try {
                //get the value of the key translation
                $columnTransObject = $model->getTranslations($columnName);
                //get the keys of the translation object
                $columnTransKeys = array_keys($columnTransObject);
                //if there is no key for the config fallback locale
                if (!in_array(config('app.fallback_locale'), $columnTransKeys)) {
                    //set the translation for the config fallback locale to the value of the first key
                    $model->setTranslation($columnName, config('app.fallback_locale'), $columnTransObject[$columnTransKeys[0]]);
                    $model->saveQuietly();
                }
            } catch (\Exception $e) {
                logger('handleModelFallbackTranslation', [$e->getMessage()]);
            }
        }
    }
}
