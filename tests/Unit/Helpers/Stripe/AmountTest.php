<?php

namespace Unit\Helpers\Stripe;

use App\Helpers\Stripe\Amount;
use TestCase;

class AmountTest extends TestCase
{
    public function testGet(): void
    {
        $this->assertEquals(Amount::get(9.99), 999);
        $this->assertEquals(Amount::get(0), 30);
        $this->assertEquals(Amount::get(5), 500);
    }
}
