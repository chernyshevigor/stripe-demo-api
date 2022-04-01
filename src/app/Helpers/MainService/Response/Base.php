<?php

namespace App\Helpers\MainService\Response;

use App\Helpers\TypedObject\{Contract, CreateFromTrait};

/**
 * @method static static createFrom(object $obj)
 */
class Base implements Contract
{
    use CreateFromTrait;

    public bool $success;
    public string $message;

    public function isSuccess(): bool
    {
        return $this->success;
    }
}
