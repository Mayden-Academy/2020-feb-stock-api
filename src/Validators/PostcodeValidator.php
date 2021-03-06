<?php

namespace App\Validators;

class PostcodeValidator
{
    private const POSTCODE_REGEX = '/([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})/m';

    /**
     * Validates postcode
     * @param string $postcode
     * @return string
     * @throws \Exception
     */
    public static function validatePostcode(string $postcode)
    {
        if(preg_match(self::POSTCODE_REGEX, $postcode)) {
            return $postcode;
        } else {
            throw new \Exception('Invalid postcode');
        }
    }
}
