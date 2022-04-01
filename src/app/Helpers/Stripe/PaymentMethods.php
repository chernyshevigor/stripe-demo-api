<?php

namespace App\Helpers\Stripe;

// todo: PHPunit
use App\Helpers\Stripe\Card\Brand;
use Laravel\Cashier\PaymentMethod;

final class PaymentMethods extends Base
{
    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function get(): array
    {
        $result = [
            'cards' => [],
            'defaultSource' => $this->customer->defaultPaymentMethod()
                ? $this->customer->defaultPaymentMethod()->asStripePaymentMethod()->id
                : ''
        ];
        $this->customer->paymentMethods()->each(function (PaymentMethod $card) use (&$result) {
            $paymentMethod = $card->asStripePaymentMethod();
            $result['cards'][] = [
                'id' => $paymentMethod->id,
                'expMonth' => $paymentMethod->card->exp_month,
                'expYear' => $paymentMethod->card->exp_year,
                'last4' => $paymentMethod->card->last4,
                'brandLogo' => Brand::getAsset($paymentMethod->card->brand)
            ];
        });
        return $result;
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function update(string $paymentMethodId): void
    {
        $this->customer->updateDefaultPaymentMethod($paymentMethodId);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function delete(string $paymentMethodId): void
    {
        $this->customer->deletePaymentMethod($paymentMethodId);
    }
}
