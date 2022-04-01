"laravel/lumen-framework" is not fully compatible with "laravel/cashier-stripe"

vendor/laravel/cashier/src/Http/Controllers/WebhookController.php
(https://github.com/laravel/cashier-stripe/blob/13.x/src/Events/WebhookHandled.php#L5) requires Illuminate\Foundation\Events\Dispatchable which is not described in the required section of [composer.json](https://github.com/laravel/cashier-stripe/blob/13.x/composer.json)

we can't simply require "laravel/framework" because it has own helpers etc.

so the dependence is temporary hard-coded (v8.80.0 of "laravel/framework")
