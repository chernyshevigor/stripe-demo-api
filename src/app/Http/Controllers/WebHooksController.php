<?php

namespace App\Http\Controllers;

use App\Helpers\Webhook;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo:https://stripe.com/docs/webhooks#acknowledge-events-immediately
 * @todo: https://stripe.com/docs/webhooks#built-in-retries
 * See Stripe CLI (local debug):
 * https://stripe.com/docs/webhooks
 * https://stripe.com/docs/api/events
 * ssh to stripe-cli or locally:
 * stripe listen --forward-to http://api.xxx.backend/stripe/webhook -j
 * stripe trigger {click tab-tab autocompletion}
 */
final class WebHooksController extends WebhookController
{
    public function handleWebhook(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        if ($payload === null) {
            return new Response;
        }
        return parent::handleWebhook($request);
    }

    /**
     * @param array $payload
     * @return Response
     * @throws \JsonException
     */
    protected function handlePaymentIntentSucceeded(array $payload): Response
    {
        // payment_intent.succeeded (perhaps + charge.succeeded ???)
        $data = $payload['data']['object'];
        if (isset($data['metadata']['action'])) {
            app()->make(Webhook::class)->send([
                'action' => $data['metadata']['action'],
                'payload' => json_encode($payload, JSON_THROW_ON_ERROR),
            ]);
        }
        return $this->successMethod();
    }

    /**
     * @param array $payload
     * @return Response
     */
    protected function handlePaymentIntentPaymentFailed(array $payload)
    {
        // payment_intent.payment_failed
        // ??? any logic
        return $this->successMethod();
    }

    /**
     * @method name is "handle" + postfix (equal to a listening (enabled_events) event name)
     * @for instance "invoice.payment_succeeded" will be equal to "handleInvoicePaymentSucceeded"
     * @link https://stripe.com/docs/api/events/list
     * @return Response
     */
    private function handleExample(Request $request): Response
    {
        // parent::handleWebhook() $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));
        $event = \Stripe\Event::constructFrom(json_decode($request->getContent(), true));
        var_dump($event->type);
        var_dump($event->data->object);
        try {
            $webHook = \Stripe\Webhook::constructEvent(
                json_decode($request->getContent(), true),
                $request->get('HTTP_STRIPE_SIGNATURE'),
                env('STRIPE_WEBHOOK_SECRET')
            );
            print_r($webHook);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            var_dump($e->getMessage());
            return new Response;
        }
        return new Response;
    }
}
