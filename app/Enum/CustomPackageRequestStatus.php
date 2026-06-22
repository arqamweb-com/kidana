<?php

namespace App\Enum;

enum CustomPackageRequestStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Closed = 'closed';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::New->value => 'New',
            self::Contacted->value => 'Contacted',
            self::Closed->value => 'Closed',
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::Contacted => 'info',
            self::Closed => 'success',
        };
    }
}
