<?php

namespace App\Upgrades;


class Upgrade60 extends BaseUpgrade
{

    public $versionName = "1.7.01";
    //Runs or migrations to be done on this version
    public function run()
    {
        $countryCodes = setting('countryCode', "GH");
        //replace AUTO with INTERNATIONAL
        $itContains = strpos($countryCodes, 'AUTO');
        if ($itContains !== false) {
            $countryCodes = str_replace('AUTO', 'INTERNATIONAL', $countryCodes);
            setting(['countryCode' => $countryCodes])->save();
        }

        //modify the delivery_addresses table to have empty string as default for country, city and state
        \DB::statement("ALTER TABLE `delivery_addresses` CHANGE `country` `country` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        \DB::statement("ALTER TABLE `delivery_addresses` CHANGE `city` `city` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        \DB::statement("ALTER TABLE `delivery_addresses` CHANGE `state` `state` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");

        //delivery addresses
        //update any record with country IS NULL to ""
        \DB::table('delivery_addresses')->whereNull('country')->update(['country' => ""]);
        //update any record with city IS NULL to ""
        \DB::table('delivery_addresses')->whereNull('city')->update(['city' => ""]);
        //update any record with state IS NULL to ""
        \DB::table('delivery_addresses')->whereNull('state')->update(['state' => ""]);
    }
}
