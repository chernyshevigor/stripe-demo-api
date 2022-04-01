<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * @url https://stripe.com/docs/api/customers
 */
final class CustomersController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, UserService $service): array
    {
        $user = $service->create($this->validateCustomer($request));
        return [
            'success' => 1,
            'customerId' => $user->stripe_id,
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, UserService $service): array
    {
        $service->update($this->validateCustomer($request));
        return [
            'success' => 1,
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateCustomer(Request $request): array
    {
        return $this->validate($request, [
            'id' => 'required|integer|numeric|min:0|not_in:0',
            'name' => 'string|nullable',
            'email' => 'email',
            'phone' => 'string|nullable',
            'postcode' => 'string|nullable',
            'location' => 'string|nullable',
            'town' => 'string|nullable',
            'address' => 'string|nullable',
            'countryCode' => 'string|nullable|min:2|max:2',
        ]);
    }

    public function retrieve(Request $request): array
    {
        return [];
    }
}
