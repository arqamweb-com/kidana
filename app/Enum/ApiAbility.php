<?php

namespace App\Enum;

enum ApiAbility: string
{
    case CatalogRead = 'catalog:read';
    case BookingsCreate = 'bookings:create';
    case BookingsRead = 'bookings:read';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CatalogRead->value => 'Read catalog (services, packages, hotels, availability)',
            self::BookingsCreate->value => 'Create and cancel bookings',
            self::BookingsRead->value => 'Read bookings',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
