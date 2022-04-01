<?php

namespace App\Helpers\MainService;

use Psr\Http\Message\UriInterface;

interface ClientContract
{
    public function setUri(string $uri): ClientContract;
    public function getUri(): UriInterface;
    public function setApiPath(string $urlPfx = ''): ClientContract;
}
