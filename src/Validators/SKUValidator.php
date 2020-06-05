<?php

namespace Portal\Validators;

class SKUValidator
{
    private const SKU_REGEX = '/^[a-z0-9A-Z]{10,20}$/';

    public static function validateSKU(string $SKU)
    {
        if (preg_match(self::PRICE_REGEX, $SKU)) {
            return $SKU;
        } else {
            throw new Exception('Invalid SKU');
        } 
    }
}