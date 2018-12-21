<?php

namespace Tests;

use Paysera\Services\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    public function convertEurProvider()
    {
        return [
            [1, 'EUR', 1],
            [1, 'USD', 1.1497],
            [1, 'JPY', 129.53],
        ];
    }

    /**
     * @dataProvider convertEurProvider
     */
    public function testConvertEur($amount, $currency, $expected)
    {
        $this->assertEquals($expected, CurrencyConverter::convertEur($amount, $currency));
    }

    public function convertToEurProvider()
    {
        return [
            [1, 'EUR', 1],
            [1.1497, 'USD', 1],
            [129.53, 'JPY', 1],
        ];
    }

    /**
     * @dataProvider convertToEurProvider
     */
    public function testConvertToEur($amount, $currency, $expected)
    {
        $this->assertEquals($expected, CurrencyConverter::convertToEur($amount, $currency));
    }

}
