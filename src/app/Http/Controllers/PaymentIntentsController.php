<?php

namespace App\Http\Controllers;

use App\Helpers\Stripe\PaymentIntent as PaymentIntentHelper;
use Illuminate\Http\Request;

final class PaymentIntentsController extends Controller
{
    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieve(PaymentIntentHelper $helper, Request $request): array
    {
        return [
            'success' => 1,
            'payment_intent' => $helper->retrieve($request->get('payment_intent_id')),
        ];
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function create(PaymentIntentHelper $helper, Request $request): array
    {
        $intent = $helper->create((float) $request->get('amount'), (array) $request->get('metadata'));
        return ['success' => 1, 'clientSecret' => $intent->client_secret, 'paymentIntentId' => $intent->id];
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PaymentIntentHelper $helper, Request $request, string $paymentIntentId): array
    {
        $data = $this->validate($request, [
            'action' => 'required|string',
            'payload' => 'required|json',
        ]);
        $helper->update($paymentIntentId, $data['action'], $data['payload']);
        return ['success' => 1];
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getSuccessed(PaymentIntentHelper $helper): array
    {
        return [
            'success' => 1,
            'payment_intents' => $helper->getSuccessed(),
        ];
    }
}
