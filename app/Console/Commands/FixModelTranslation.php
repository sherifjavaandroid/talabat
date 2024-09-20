<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixModelTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //accept table name as argument
    protected $signature = 'fix:model:translation {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix those models that have a translation but not the original model in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $isProd = env('APP_ENV') == 'production';

        try {

            DB::beginTransaction();
            //get the table name from the argument
            $table = $this->argument('table');
            //get the model name from the table name
            $modelName = ucfirst(\Str::singular($table));

            //
            $this->info('Start FixModelTranslation: ' . $modelName);
            //get the model class
            $modelClass = "App\\Models\\$modelName";
            $model = new $modelClass;
            //get all the keys of the trans array
            $translatable = $model->translatable;
            //if the model is not translatable
            if (empty($translatable)) {
                $this->error('Model is not translatable');
                return;
            }

            //get all the model values
            $modelValues = $model->all();
            foreach ($modelValues as $modelRow) {
                //loop through the translatable keys
                foreach ($translatable as $columnName) {
                    //get the column value from the table
                    $columnValue = $modelRow->getTranslations($columnName);
                    //if the column value is not json
                    if (!$this->isJson($columnValue)) {
                        //then set the column value to json with the key en
                        $modelRow->setTranslation($columnName, config('app.fallback_locale', 'en'), $columnValue);
                        $this->info('String To Json: ' . $modelRow->id . ' ' . $columnName);
                        continue;
                    }

                    //get the deepest json object
                    if (is_array($columnValue) && empty($columnValue)) {
                        $rawModel = DB::table($table)->where('id', $modelRow->id)->first();
                        $modelJson = collect($rawModel)->toArray();
                        $columnValue = $modelJson[$columnName];
                        // logger('Original columnValue', [$columnValue]);
                        $columnValue = stripslashes($columnValue);
                        $columnValue = str_replace("\"{", '{', $columnValue);
                        $columnValue = str_replace("}\"", '}', $columnValue);
                        // logger('str_replace columnValue', [$columnValue]);
                        $columnValue = json_decode($columnValue, true);
                        // logger('Original columnValue', [$columnValue]);
                    }
                    if (!$isProd) {
                        logger('columnValue', [$columnValue]);
                    }

                    $columnValue = json_encode($columnValue);
                    $deepestJsonObject = $this->findDeepestJsonObject($columnValue);

                    if (!$isProd) {
                        logger('deepestJsonObject', [$columnName, $deepestJsonObject]);
                    }
                    // continue;
                    //if the deepest json object is not null
                    // $modelRow->$columnName = json_encode($deepestJsonObject);
                    if ($deepestJsonObject != null) {
                        $modelRow->setTranslations($columnName, $deepestJsonObject);
                    } else {
                        logger('Null/Empty Value', [$columnName, $deepestJsonObject]);
                    }
                    $this->info('DeepestJsonObject: ' . $modelRow->id . ' ' . $columnName);
                }


                //save the model
                $modelRow->saveQuietly();
            }


            //
            $this->info('End FixModelTranslation: ' . $modelName);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logger('FixModelTranslation error', [$e->getMessage()]);
            logger('FixModelTranslation error', [$e]);
        }

        return 0;
    }







    ///
    function isJson($string)
    {
        if (is_array($string)) {
            return true;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function findDeepestJsonObject($json)
    {
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return null;
        }

        $deepestLevel = 0;
        $deepestObject = null;

        $findDeepest = function ($object, $level) use (&$findDeepest, &$deepestLevel, &$deepestObject) {
            if (is_array($object)) {
                foreach ($object as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $findDeepest($value, $level + 1);
                    } else {
                        if ($level > $deepestLevel) {
                            $deepestLevel = $level;
                            $deepestObject = $object;
                        }
                    }
                }
            } else if (is_object($object)) {
                foreach ($object as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $findDeepest($value, $level + 1);
                    } else {
                        if ($level > $deepestLevel) {
                            $deepestLevel = $level;
                            $deepestObject = $object;
                        }
                    }
                }
            }
        };

        $findDeepest($data, 0);

        return $deepestObject;
    }
}
