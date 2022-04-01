<?php

namespace App\Helpers\MainService;

use App\Helpers\Traits\ServerRequestTrait;
use App\Helpers\MainService\DTO\Customer;
use App\Helpers\MainService\Response\PaymentIntent;
use App\Helpers\MainService\Request\PaymentIntent as PIRequest;

final class Request
{

    use ServerRequestTrait;

    private Client $client;

    public static function instance(): self
    {
        $self = new static();
        return $self->setClient(new Client($self->getApiUri()));
    }

    private function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function createCustomer(Customer $customer): object
    {
        return $this->client->customerCreate($this->getServerRequest(), $customer);
    }

    public function updateCustomer(Customer $customer): object
    {
        return $this->client->customerUpdate($this->getServerRequest(), $customer);
    }

    public function getPaymentIntent(int $uid, string $intent): array
    {
        $obj = $this->client->getPaymentIntent($this->getServerRequest(), $uid, $intent);
        return isset($obj->payment_intent)
            ? json_decode(json_encode($obj->payment_intent), true)
            : [];
    }

    /**
     * @throws \Exception
     */
    public function createPaymentIntent(int $uid, ?float $amount = null, array $metadata = []): PaymentIntent
    {
        return $this->client->createPaymentIntent(
            $this->getServerRequest(),
            (new PIRequest())
                ->setAmount($amount)
                ->setMetadata($metadata)
                ->setUserId($uid)
        );
    }

    public function getSuccessedPaymentIntents(int $uid): array
    {
        try {
            $obj = $this->client->getSuccessedPaymentIntents($this->getServerRequest(), $uid);
            return $obj->payment_intents ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
