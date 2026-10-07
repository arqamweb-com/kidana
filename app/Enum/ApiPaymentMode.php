<?php

namespace App\Enum;

enum ApiPaymentMode: string
{
    case PartnerCollect = 'partner_collect';
    case Fawry = 'fawry';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PartnerCollect->value => 'Partner collects (monthly settlement)',
            self::Fawry->value => 'Customer pays via Fawry link',
        ];
    }
}
