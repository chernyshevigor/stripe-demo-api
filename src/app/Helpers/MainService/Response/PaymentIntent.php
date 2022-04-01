<?php

namespace App\Helpers\MainService\Response;

class PaymentIntent extends Base
{
    public string $clientSecret;
    public string $paymentIntentId;
}
