<?php

namespace App\Providers;

use App\Helpers\Stripe\{PaymentMethods, SetupIntent, PaymentIntent};
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentMethods::class, function() {
            return new PaymentMethods();
        });
        $this->app->bind(SetupIntent::class, function() {
            return new SetupIntent();
        });
        $this->app->bind(PaymentIntent::class, function() {
            return new PaymentIntent();
        });
    }
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Cashier::ignoreRoutes(); // Cashier's WebHooksController has Laravel routing
        Cashier::useCustomerModel(User::class);
//        Cashier::calculateTaxes(); // ???
//        Cashier::useSubscriptionModel(Subscription::class); // if we need to override DB model: Subscription extends \Laravel\Cashier\Subscription
    }
}
