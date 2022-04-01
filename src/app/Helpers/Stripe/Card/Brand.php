<?php

namespace App\Helpers\Stripe\Card;

class Brand
{
    public static function getAsset(?string $name): string
    {
        return match($name) {
            'amex' => 'amex.svg',
            'diners' => 'diners.svg',
            'discover' => 'discover.svg',
            'jcb' => 'jcb.svg',
            'mastercard' => 'mastercard.svg',
            'unionpay' => 'unionpay.svg',
            'visa' => 'visa.svg',
            default => 'unknown.svg'
        };
    }
}
