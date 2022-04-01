<?php

namespace App\Helpers\MainService\Request;

use JsonSerializable;

class PaymentIntent implements JsonSerializable
{
    private ?float $amount;
    private ?array $metadata;
    private int $userId;

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return ['amount' => $this->amount, 'metadata' => $this->metadata, 'app_user_id' => $this->userId];
    }
}
