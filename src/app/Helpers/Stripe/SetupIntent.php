<?php

namespace App\Helpers\Stripe;

// todo: PHPunit
final class SetupIntent extends Base
{
    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function setup(): array
    {
        $intent = $this->customer->createSetupIntent([
            'customer' => $this->customer->stripeId(),
            'payment_method_types' => ['card']
        ]);
        return ['clientSecret' => $intent->client_secret];
    }
}
