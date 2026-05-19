<?php

namespace App\Enum;

enum PackageOrderAction: string
{
    case CustomForm = 'custom_form';
    case FawryPayment = 'fawry_payment';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CustomForm->value => 'Custom request form',
            self::FawryPayment->value => 'Fawry payment',
        ];
    }
}
