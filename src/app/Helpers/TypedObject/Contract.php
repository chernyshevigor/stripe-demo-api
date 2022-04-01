<?php

namespace App\Helpers\TypedObject;

interface Contract
{
    public static function createFrom(object $obj): object;
    public static function getStub(): Contract;
}
