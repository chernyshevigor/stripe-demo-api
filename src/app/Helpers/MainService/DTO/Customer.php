<?php

namespace App\Helpers\MainService\DTO;

use App\Helpers\TypedObject\Contract;
use App\Helpers\TypedObject\CreateFromTrait;

final class Customer implements Contract
{
    use CreateFromTrait;

    public $id;
    public $name;
    public $email;
    public $postcode;
    public $countryCode;
    public $location;
    public $town;
    public $address;
    public $phone;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;
        return $this;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }
}