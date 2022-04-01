<?php

namespace Unit\Controllers;

use TestCase;

class WebHooksControllerTest extends TestCase
{
    public function testIndexSuccess(): void
    {
        $this->get('/stripe/webhook');
        self::assertEquals('200', $this->response->status());
    }
}
