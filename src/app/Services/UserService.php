<?php

namespace App\Services;

use App\Models\User;

// https://stripe.com/docs/api/customers/object
// https://laravel.com/docs/8.x/billing#creating-customers
// https://stripe.com/docs/api/customers/create
// https://laravel.com/docs/8.x/billing#updating-customers
// https://stripe.com/docs/api/customers/update
final class UserService
{
    public function create(array $data): User
    {
        $user = User::where(['app_user_id' => $data['id']])->first();
        if (empty($user->stripe_id)) {
            $user = new User();
            $user->app_user_id = $data['id'];
            $user->createOrGetStripeCustomer($this->buildStripeCustomerArray($data));
        }
        return $user;
    }

    private function buildStripeCustomerArray(array $data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => [
                'city' => $data['town'],
                'country' => $data['countryCode'],
                'line1' => $data['address'],
                'postal_code' => $data['postcode'],
                'state' => $data['location'],
            ],
            'metadata' => ['app_user_id' => $data['id']],
        ];
    }

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(array $data): void
    {
        /* @var User $user */
        $user = User::where(['app_user_id' => $data['id']])->firstOrFail();
        if ($user->stripe_id) {
            $user->updateStripeCustomer($this->buildStripeCustomerArray($data));
        }
    }
}
