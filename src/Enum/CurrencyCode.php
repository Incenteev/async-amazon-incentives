<?php

namespace Incenteev\AsyncAmazonIncentives\Enum;

final class CurrencyCode
{
    public const AED = 'AED';
    public const AUD = 'AUD';
    public const CAD = 'CAD';
    public const EUR = 'EUR';
    public const GBP = 'GBP';
    public const JPY = 'JPY';
    public const MXN = 'MXN';
    public const PLN = 'PLN';
    public const SAR = 'SAR';
    public const SEK = 'SEK';
    public const SGD = 'SGD';
    public const TRY = 'TRY';
    public const USD = 'USD';

    public static function exists(string $value): bool
    {
        return isset([
            self::AED => true,
            self::AUD => true,
            self::CAD => true,
            self::EUR => true,
            self::GBP => true,
            self::JPY => true,
            self::MXN => true,
            self::PLN => true,
            self::SAR => true,
            self::SEK => true,
            self::SGD => true,
            self::TRY => true,
            self::USD => true,
        ][$value]);
    }
}
