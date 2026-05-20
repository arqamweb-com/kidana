<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Created = 'created';
    case Unpaid = 'unpaid';
    case Processing = 'processing';
    case Paid = 'paid';
    case Failed = 'failed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case PartialRefunded = 'partial_refunded';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Pending->value => 'Pending',
            self::Created->value => 'Created',
            self::Unpaid->value => 'Unpaid',
            self::Processing->value => 'Processing',
            self::Paid->value => 'Paid',
            self::Failed->value => 'Failed',
            self::Expired->value => 'Expired',
            self::Cancelled->value => 'Cancelled',
            self::Refunded->value => 'Refunded',
            self::PartialRefunded->value => 'Partial refunded',
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Paid => 'success',
            self::Pending, self::Created, self::Unpaid, self::Processing => 'warning',
            self::Failed, self::Expired, self::Cancelled => 'danger',
            self::Refunded, self::PartialRefunded => 'info',
        };
    }
}
