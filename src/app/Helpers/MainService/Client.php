<?php

namespace App\Helpers\MainService;

use Exception;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ServerRequestInterface;
use App\Helpers\MainService\Response\Base as BaseResponse;
use App\Helpers\MainService\Response\PaymentIntent;
use App\Helpers\MainService\Request\PaymentIntent as PIRequest;
use App\Helpers\SingletonTrait;
use App\Helpers\Traits\ClientTrait;
use App\Helpers\MainService\DTO\Customer;
use RuntimeException;

final class Client
{
    use SingletonTrait;
    use ClientTrait;

    public function __construct(string $apiUri)
    {
        $this->setUri($apiUri)->setApiPath('stripe');
    }

    public function customerCreate(ServerRequestInterface $request, Customer $customer): BaseResponse
    {
        $response = BaseResponse::createFrom($this->performRequest('/customers/create',
            $request, [RequestOptions::JSON => $customer]
        ));
        if (!$response->isSuccess()) {
            throw new RuntimeException($response->message);
        }
        return $response;
    }

    public function customerUpdate(ServerRequestInterface $request, Customer $customer): BaseResponse
    {
        $response = BaseResponse::createFrom($this->performRequest('/customers/update',
            $request, [RequestOptions::JSON => $customer]
        ));
        if (!$response->isSuccess()) {
            throw new RuntimeException($response->message);
        }
        return $response;
    }

    public function getPaymentIntent(ServerRequestInterface $request, int $uid, string $intent): BaseResponse
    {
        $response = BaseResponse::createFrom($this->performRequest('/payment_intents/retrieve',
            $request, [RequestOptions::JSON => ['app_user_id' => $uid, 'payment_intent_id' => $intent]]
        ));
        if (!$response->isSuccess()) {
            throw new RuntimeException($response->message);
        }
        return $response;
    }

    /**
     * @throws Exception
     */
    public function createPaymentIntent(ServerRequestInterface $request, PIRequest $paymentIntent): PaymentIntent
    {
        $response = PaymentIntent::createFrom($this->performRequest('/payment_intents/create',
            $request, [RequestOptions::JSON => $paymentIntent->jsonSerialize()]
        ));
        if (!$response->isSuccess()) {
            throw new RuntimeException($response->message);
        }
        return $response;
    }

    public function getSuccessedPaymentIntents(ServerRequestInterface $request, int $uid): BaseResponse
    {
        $response = BaseResponse::createFrom($this->performRequest('/payment_intents/successed',
            $request, [RequestOptions::JSON => ['app_user_id' => $uid]]
        ));
        if (!$response->isSuccess()) {
            throw new RuntimeException($response->message);
        }
        return $response;
    }
}
