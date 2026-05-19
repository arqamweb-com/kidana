<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Pending->value => 'Pending',
            self::Paid->value => 'Paid',
            self::Failed->value => 'Failed',
            self::Expired->value => 'Expired',
            self::Cancelled->value => 'Cancelled',
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Paid => 'success',
            self::Pending => 'warning',
            self::Failed, self::Expired, self::Cancelled => 'danger',
        };
    }
}
