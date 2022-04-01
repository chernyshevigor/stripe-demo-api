<?php

namespace App\Http\Controllers;

use App\Helpers\Stripe\PaymentMethods;
use Illuminate\Http\Request;

final class PaymentMethodsController extends Controller
{
    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function get(PaymentMethods $helper): array
    {
        return $helper->get();
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function update(PaymentMethods $helper, Request $request): array
    {
        $helper->update($request->get('paymentMethodId'));
        return ['success' => 'true'];
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function delete(string $paymentMethodId, PaymentMethods $helper): array
    {
        $helper->delete($paymentMethodId);
        return ['success' => 'true'];
    }
}
