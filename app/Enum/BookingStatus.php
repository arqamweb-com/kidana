<?php

namespace App\Enum;

enum BookingStatus: string
{
    case Pending = 'pending';
    case AwaitingPayment = 'awaiting_payment';
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
            self::AwaitingPayment->value => 'Awaiting payment',
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
            self::AwaitingPayment => 'warning',
            self::Failed, self::Expired, self::Cancelled => 'danger',
            self::Pending => 'gray',
        };
    }
}
