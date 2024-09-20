<?php

use App\Models\Order;
use App\Models\VendorSetting;
use GeoSot\EnvEditor\Facades\EnvEditor;
use Illuminate\Support\Str;


function isRTL(): bool
{
    return app()->getLocale() == "ar" || setting('localeCode') == "ar";
}

function inProduction(): bool
{
    return app()->environment('production');
}

function genFileName($file, $length = 5)
{
    //check if file is string data
    if (is_string($file)) {
        $sections = explode(".", $file);
        $ext = end($sections);
    } else {
        $ext  = $file->extension();
    }
    $name = Str::random($length) . "-" . time() . "." . $ext;
    return $name;
}


//get vendor settings or default settings
function driverSearchRadius(Order $order = null)
{

    if ($order == null || empty($order->vendor_id)) {
        return setting('driverSearchRadius', 10);
    }
    //
    $vendorSetting = VendorSetting::where('vendor_id', $order->vendor_id)->first();
    if (empty($vendorSetting)) {
        return setting('driverSearchRadius', 10);
    } else {
        $settings = json_decode($vendorSetting->settings, true) ?? [];
        return $settings['driver_search_radius'] ?? setting('driverSearchRadius', 10);
    }
}


function maxDriverOrderAtOnce(Order $order = null, $default = 1)
{
    if ($order == null || empty($order->vendor_id)) {
        return setting('maxDriverOrderAtOnce', $default);
    }
    //
    $vendorSetting = VendorSetting::where('vendor_id', $order->vendor_id)->first();
    if (empty($vendorSetting)) {
        return setting('maxDriverOrderAtOnce', $default);
    } else {
        $settings = json_decode($vendorSetting->settings, true) ?? [];
        return $settings['max_driver_order_at_once'] ?? setting('maxDriverOrderAtOnce', $default);
    }
}


function maxDriverOrderNotificationAtOnce(Order $order = null, $default = 1)
{
    if ($order == null || empty($order->vendor_id)) {
        return setting('maxDriverOrderNotificationAtOnce', $default);
    }
    //
    $vendorSetting = VendorSetting::where('vendor_id', $order->vendor_id)->first();
    if (empty($vendorSetting)) {
        return setting('maxDriverOrderNotificationAtOnce', $default);
    } else {
        $settings = json_decode($vendorSetting->settings, true) ?? [];
        return $settings['max_driver_order_notification_at_once'] ?? setting('maxDriverOrderNotificationAtOnce', $default);
    }
}


function isMediaImage($media)
{
    return in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
}



function setEnv($key, $value, $group = null)
{
    if (EnvEditor::keyExists($key)) {
        EnvEditor::editKey($key, $value);
    } else {
        $options = [];
        if ($group != null && is_int($group)) {
            $options['group'] = $group;
        }
        try {
            EnvEditor::addKey($key, $value, $options);
        } catch (\Exception $ex) {
            EnvEditor::addKey($key, $value);
        }
    }
}


function fetchDataByLocation()
{
    return (bool) setting('enableFatchByLocation', 0);
}


function allowOldUnEncryptedOrder()
{
    return (bool) setting('allowOldUnEncryptedOrder', 1);
}


//create function that will accept two url, and return the url that is not 404
function getValidValue($url1, $url2)
{

    if (empty($url1)) {
        return $url2;
    }

    return $url1;
}


function appLogo()
{
    return getValidValue(setting('websiteLogo'), asset('images/logo.png'));
}