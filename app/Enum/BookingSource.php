<?php

namespace App\Enum;

enum BookingSource: string
{
    case Web = 'web';
    case Api = 'api';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Web->value => 'Website',
            self::Api->value => 'Partner API',
        ];
    }
}
