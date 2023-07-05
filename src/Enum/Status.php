<?php

namespace Incenteev\AsyncAmazonIncentives\Enum;

final class Status
{
    public const FAILURE = 'FAILURE';
    public const RESEND = 'RESEND';
    public const SUCCESS = 'SUCCESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAILURE => true,
            self::RESEND => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
