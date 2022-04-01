<?php

namespace App\Helpers\Stripe;

use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;

// todo: PHPunit
abstract class Base
{
    /**
     * @var Billable
     */
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::user();
    }
}
