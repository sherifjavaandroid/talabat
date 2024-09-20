<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AutoTranslator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:translate {folder?} {languageCode?}';
    //make the folder and language code optional
    // protected $signature = 'auto:translate {folder?} {languageCode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto translate new lang strings';

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

        try {
            //
            $translate = new TranslateClient(
                [
                    "keyFile" => json_decode(Storage::get('vault/firebase_service.json'), true),
                ]
            );

            //
            $folder = $this->argument('folder');
            if (!empty($folder)) {
                $path = base_path() . "/resources/lang/$folder";
                //create folder if not exist
                if (!File::exists($path)) {
                    File::makeDirectory($path);
                }

                //
                $fullLangFilePath = base_path() . "/website-lang-full.txt";
                $newLangFilePath = base_path() . "/website-lang.txt";
            } else {
                $path = base_path() . "/resources/lang";
                $fullLangFilePath = base_path() . "/lang-full.txt";
                $newLangFilePath = base_path() . "/lang.txt";
            }

            //
            //load the new lang strings
            $newLang = File::get($newLangFilePath);
            $newLangArray = explode("\n", $newLang);

            //load the language codes
            $codes = config('backend.languageCodes');
            //if the language code is specified in the command
            $languageCode = $this->argument('languageCode');
            if (!empty($languageCode)) {
                $codes = [$languageCode];
            }

            foreach ($codes as $code) {
                //
                $this->info("Translating ==> $code");
                $this->newLine();

                //
                $newTranslatedData = collect();
                //loop through the strings in new lang file
                $this->withProgressBar($newLangArray, function ($newLangString) use ($code, $translate, $newTranslatedData) {
                    if ($code != "en") {
                        //
                        $result = $translate->translate($newLangString, [
                            'target' => $code
                        ]);
                        // logger("transledString", [$result]);
                        $transledString = $result["text"];
                        //
                        $newTranslatedData->put($newLangString, $transledString);
                    } else {
                        $newTranslatedData->put($newLangString, $newLangString);
                    }
                });
                //
                $newTranslatedDataJson = json_decode($newTranslatedData->toJson(), $flag = JSON_UNESCAPED_UNICODE);
                // $newTranslatedDataJson = json_encode($newTranslatedDataJson, JSON_UNESCAPED_UNICODE);
                //load the language file
                $langFilePath = $path . "/" . $code . ".json";
                //create file if not exist
                if (!File::exists($langFilePath)) {
                    File::put($langFilePath, "{}");
                }
                //
                $languageFile = File::get($langFilePath);
                $langJson = json_decode($languageFile, $flag = JSON_UNESCAPED_UNICODE);
                //merging two json objects
                $newFullLangJson = array_merge($newTranslatedDataJson, $langJson);
                File::replace($langFilePath, json_encode($newFullLangJson, JSON_UNESCAPED_UNICODE));
                $this->newLine();
                $this->info("Done Translating: $code");
                $this->newLine();
                $this->info("----------------------");

                // logger("Old Lang", [$langJson]);
                // logger("New Lang", [$newTranslatedDataJson]);
                // logger("New Full Lang", [$newFullLangJson]);
            }

            //add the content of new lang file to lang-full.txt
            File::append($fullLangFilePath, "\n" . $newLang);
            File::replace($newLangFilePath, "");
            //delete the content of the new lang file
        } catch (\Exception $error) {
            $this->newLine();
            $this->error($error->getMessage());
            logger("Error", [$error]);
        }
        return 0;
    }
}
