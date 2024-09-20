<?php

namespace App\Services\Core;

class ExtraPhoneNumberValidationService
{
    static public function validateCustomRegex(mixed $value): bool
    {

        // $regex = "/^(\+225)?\s?(\d{2}\s?){4}$/";
        $regex = env("CUSTOM_PHONE_NUMBER_REGEX", "");
        if (empty($regex)) {
            return false;
        }
        return preg_match($regex, $value);
    }
}
