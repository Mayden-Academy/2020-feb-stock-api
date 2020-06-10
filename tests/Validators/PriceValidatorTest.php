<?php

namespace Tests\Validators;

use App\Validators\PriceValidator;
use Tests\TestCase;

class PriceValidatorTest extends TestCase
{
    public function testValidatePriceSuccess()
    {
        $price = '12345.55';
        $result = PriceValidator::validatePrice($price);
        $this->assertEquals('12345.55', $result);
    }

    public function testValidatePriceFailure_Letters()
    {
        $price = 'ABCD';
        $this->expectExceptionMessage('Invalid price');
        PriceValidator::validatePrice($price);
    }

    public function testValidatePriceFailure_InvalidPrice()
    {
        $price = '12345.555';
        $this->expectExceptionMessage('Invalid price');
        PriceValidator::validatePrice($price);
    }

    public function testValidatePriceEmpty()
    {
        $price = '';
        $this->expectExceptionMessage('Must provide price and be less than 255 characters');
        PriceValidator::validatePrice($price);
    }

    public function testValidatePriceTooHigh()
    {
        $price = '19817678629620665857268939702373019737840166100519735271333668622332128548725478085542947069540991549996429960612697364361917459437918537729309808031042856150356146660311678052257062703375622747663806794091131352873806128866900516265521670179620736396676.99';
        $this->expectExceptionMessage('Must provide price and be less than 255 characters');
        PriceValidator::validatePrice($price);
    }

    public function testValidatePriceMalformed()
    {
        $price = [12345];
        $this->expectException(\TypeError::class);
        PriceValidator::validatePrice($price);
    }
}
