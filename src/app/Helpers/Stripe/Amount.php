<?php

namespace App\Helpers\Stripe;

/**
 * https://stripe.com/docs/currencies#zero-decimal
 * https://stripe.com/docs/currencies/conversions
 * https://stripe.com/docs/api/charges/create#create_charge-amount
 * https://stripe.com/docs/api/payment_intents/create#create_payment_intent-amount
 */
class Amount
{
    /**
     * But amount is required for creating a payment intent. Payment intent's client_secret is required for stripe.js
     * form rendering. That's why if amount is empty a payment intent with minimum available amount is created. When
     * user changes the amount, the payment intent is updated too.
     * https://stripe.com/docs/currencies#minimum-and-maximum-charge-amounts
     */
    private const MIN_AMOUNT = 30;

    public static function get(float $value): int
    {
        return empty($value)
            ? self::MIN_AMOUNT
            : (int) ($value * 100);
    }
}
