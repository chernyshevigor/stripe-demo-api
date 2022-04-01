<?php

namespace App\Helpers\Stripe;

use App\Helpers\Webhook;

class PaymentIntent extends Base
{
    public function update(string $paymentIntentId, string $validateUrl, string $payload): void
    {
        $response = app()->make(Webhook::class)->send([
            'action' => $validateUrl,
            'payload' => $payload,
            'user_id' => $this->customer->app_user_id,
        ]);

        if (!$response['success']) {
            throw new \UnexpectedValueException();
        }
        $this->customer::stripe()
            ->paymentIntents
            ->update($paymentIntentId, ['amount' => Amount::get($response['amount'])]);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieve(string $intentId): \Stripe\PaymentIntent
    {
        return $this->customer::stripe()->paymentIntents->retrieve($intentId);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\PaymentIntent[]
     */
    public function getSuccessed(): array
    {
        $result = [];
        // @todo: ->all() is not good
        foreach ($this->customer::stripe()->paymentIntents->all() as $intent) {
            /* @var \Stripe\PaymentIntent $intent */
            if ($intent->status !== 'succeeded' || $this->customer->stripe_id !== $intent->customer) {
                // @todo: how can we request paymentIntents by customer?
                continue;
            }
            $result[] = $intent;
        }
        return $result;
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Exception
     */
    public function create(float $amount, array $metadata): \Stripe\PaymentIntent
    {
        return $this->customer::stripe()->paymentIntents->create([
            'amount' => Amount::get($amount),
            'currency' => env('CASHIER_CURRENCY', 'gpb'),
            'payment_method_types' => ['card'],
            'setup_future_usage' => 'off_session', // to save a card during payment
            'customer' => $this->customer->stripeId(),
            'metadata' => $metadata,
        ]);
    }
}
