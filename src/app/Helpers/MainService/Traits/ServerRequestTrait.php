<?php

namespace App\Helpers\Traits;

use GuzzleHttp\Psr7\ServerRequest as PSR7ServerReqeust;
use Psr\Http\Message\ServerRequestInterface;

trait ServerRequestTrait
{
    private function getServerRequest(): ServerRequestInterface
    {
        return $this->createServerRequest()->withCookieParams($_COOKIE);
    }

    private function createServerRequest(): ServerRequestInterface
    {
        return (new PSR7ServerReqeust('GET', ''))->withHeader(
            'Referer',
            'https://' . env('CFG_DOMAIN_NAME')
        );
    }
}
