<?php


namespace App\Services;

class TranslationFixService
{

    public function generateFallbackTranslation()
    {
        //fetch all models that have the translatable trait
        $models = $this->getModels();
        //loop through the models
        foreach ($models as $model) {
            //fetch records of the model
            $records = $model::all();
            //loop through the records
            foreach ($records as $record) {
                //handle the fallback translation
                $this->handleModelFallbackTranslation($record);
            }
        }
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
                if (empty($columnTransKeys)) {
                    continue;
                }
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

    public function getModels(int $limit = null)
    {
        $models = [];
        $modelPath = app_path('Models');
        $files = \File::allFiles($modelPath);
        foreach ($files as $file) {
            $model = $this->getModelName($file);
            if ($this->hasTranslatableTrait($model)) {
                $models[] = $model;
            }
        }
        //limit the number of models to be fixed
        if (!is_null($limit) && $limit > 0 && count($models) > $limit) {
            $models = array_slice($models, 0, $limit);
        }
        return $models;
    }

    public function getModelName($file)
    {
        $model = str_replace('.php', '', $file->getFilename());
        $model = 'App\\Models\\' . $model;
        return $model;
    }

    public function hasTranslatableTrait($model)
    {
        $traits = class_uses($model);
        if (in_array('App\Traits\HasTranslations', $traits)) {
            return true;
        }
        return false;
    }




    // fix arabic characters for models that have the translatable trait
    public function fixArabicCharacters()
    {
        //fetch all models that have the translatable trait
        $models = $this->getModels();
        //loop through the models
        foreach ($models as $model) {
            //fetch records of the model
            $records = $model::all();
            //loop through the records
            foreach ($records as $record) {
                $this->handleModelArabicTranslation($record);
            }
        }
    }

    public function handleModelArabicTranslation($model)
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
                $arabicValue = $model->getTranslation($columnName, 'ar') ?? "";
                //if value is empty continue
                if (empty($arabicValue)) {
                    continue;
                }
                //
                $arabicValue = mb_convert_encoding($arabicValue, 'UTF-8', 'UTF-8');
                $model->setTranslation($columnName, "ar", $arabicValue);
                $model->saveQuietly();
            } catch (\Exception $e) {
                logger('handleModelArabicTranslation', [$e->getMessage()]);
            }
        }
    }
}
