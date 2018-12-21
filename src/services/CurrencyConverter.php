<?php

namespace Paysera\Services;

class CurrencyConverter
{
    const EUR_CONVERSION = [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53,
    ];

    public static function convertEur($amount, $currency): float
    {
        $result = $amount * self::EUR_CONVERSION[$currency];

        return (float) $result;
    }

    public static function convertToEur($amount, $currency): float
    {
        $result = $amount / self::EUR_CONVERSION[$currency];

        return (float) $result;
    }
}
