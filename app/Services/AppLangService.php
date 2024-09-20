<?php


namespace App\Services;

class AppLangService
{

    public static $originalLocale = "en";


    public static function restoreLocale()
    {
        $locale = self::$originalLocale;
        app()->setLocale($locale);
    }

    public static function tempLocale($newLocale = "en")
    {
        self::$originalLocale = app()->getLocale();
        app()->setLocale($newLocale);
    }


    //function that accepts a closure and runs it with a temporary locale
    public static function withLocale($closure, $newLocale = "en")
    {
        self::tempLocale($newLocale);
        $closure();
        self::restoreLocale();
    }
}
