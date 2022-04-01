<?php

namespace App\Http\Controllers;

use App\Helpers\Stripe\SetupIntent;

final class SecretController extends Controller
{
    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function setup(SetupIntent $helper): array
    {
        return $helper->setup();
    }
}
