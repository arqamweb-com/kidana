<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'admin';
    case Editor = 'editor';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Editor => 'warning',
        };
    }
}
