## Billing API via Stripe

Demo of Stripe REST API for a monolith backend and JS application (TODO - desc).

There is only one dependence - the backend model user UID ('app_user_id').
The API (proxy) is based on Lumen framework and Laravel Cashier (Stripe)
* https://lumen.laravel.com/docs/8.x
* https://laravel.com/docs/8.x/billing
* https://stripe.com/docs

---

### URIs
* backend API http://api.xxx.backend/stripe/{ENDPOINT} (only internal calls - see WebSourceMiddleware)
* frontend API https://api.xxx.com/stripe/{ENDPOINT} (behind the CORS - AuthServiceProvider::api)

---

## Installation on a virtual / local machine.

```console
[optional] ssh your_backend
cd /srv/www
git clone https://github.com/chernyshevigor/stripe-demo-api.git stripe-api-backend
cd stripe-api-backend
composer install --no-dev --prefer-dist -o
[optional production] ln -s /srv/www/stripe-api-backend/services/env/.env_prod .env
php artisan migrate
[web server] update NGINX configuration and run "sudo nginx -s reload"
[web server] update NGINX Unit configuration and run "sudo unit loadconfig" or add a new configuration for other Web server to serve http://api.xxx.backend/stripe/
```

---

### Dev. notes.

* src/bootstrap/app.php->register(CashierServiceProvider::class) + AppServiceProvider - Cashier::useCustomerModel(User::class)

* To get a customer locally run UserSeeder: 
```console
cd /srv/www/stripe-api-backend
php artisan db:seed
```
You can change `stripe`.`users` `app_user_id` on your app user id.

* [Stripe CLI](https://stripe.com/docs/stripe-cli) powerful tool

It has autocompletion.
```console
stripe --help
stripe events list
stripe events retrieve evt_XXX
stripe customers list
stripe customers list | jq .data[].id
stripe customers retrieve cus_XXX
stripe logs tail
...
```

**Stripe CLI example**:
<details><summary>stripe customers list --limit 1</summary>
<p>

```json
{
  "object": "list",
  "data": [
    {
      "id": "cus_xxx",
      "object": "customer",
      "address": {
        "city": null,
        "country": null,
        "line1": null,
        "line2": null,
        "postal_code": null,
        "state": null
      },
      "balance": 0,
      "created": 1643007406,
      "currency": null,
      "default_source": null,
      "delinquent": false,
      "description": null,
      "discount": null,
      "email": "stripe@example.com",
      "invoice_prefix": "xxx",
      "invoice_settings": {
        "custom_fields": null,
        "default_payment_method": null,
        "footer": null
      },
      "livemode": false,
      "metadata": {
      },
      "name": null,
      "phone": null,
      "preferred_locales": [

      ],
      "shipping": null,
      "tax_exempt": "none"
    }
  ],
  "has_more": true,
  "url": "/v1/customers"
}
```

</p>
</details>

## Endpoints for backend (internal) app:

* POST /customers/create

Request:
```json
{
  "id": "{int}",
  "name": "{string}",
  "phone": "{string}",
  "town": "{string}",
  "country": "{string}",
  "address": "{string}",
  "postcode": "{string}",
  "location": "{string}"
}
```

Response:
```json
{
  "success": "{int_0_1}",
  "customerId": "{__stripe_id__string}"
}
```

* POST /customers/update

Request:
```json
{
  "id": "{int}",
  "name": "{string}",
  "phone": "{string}",
  "town": "{string}",
  "country": "{string}",
  "address": "{string}",
  "postcode": "{string}",
  "location": "{string}"
}
```

Response:
```json
{
  "success": "{int_0_1}"
}
```

* POST /payment_intents/retrieve

Request:
```json
{
  "app_user_id": "{int}",
  "payment_intent_id": "{string}"
}
```

Response:
```json
{
  "success": "{int_0_1}",
  "payment_intent": {
    "object"
  }
}
```

* POST /payment_intents/successed

Request:
```json
{
  "app_user_id": "{int}"
}
```

Response:
```json
{
  "success": "{int_0_1}",
  "payment_intents": {
    "objects"...
  }
}
```

* POST /payment_intents/create 

Request:
```json
{
  "app_user_id": "{int}",
  "amount": "{float_amount}",
  "metadata": "{__metadata__object}"
}
```

Response:
```json
{
  "success": "{int_0_1}",
  "clientSecret": "{string}",
  "paymentIntentId": "{string}"
}
```

* POST /payment_intents/update

Request:
```json
{
  "success": "{int_0_1}",
  "app_user_id": "{int}",
  "paymentIntentId": "{string}",
  "action": "string",
  "payload": "json"
}
```

Response:
```json
{
  "success": "{int_0_1}"
}
```

## Endpoints for frontend app:

* POST /payment_methods

Request:
```json
```

Response:
```json
{
  "cards": [
    {
      "id": "{id}",
      "expMonth": "{id}",
      "expYear": "{id}",
      "last4": "{id}",
      "brandLogo": "{string}"
    }
  ],
  "defaultSource": null
}
```

* PATCH /payment_secret/{paymentIntentId}

Request:
```json
{
  "amount": "{float_amount}"
}
```

Response:
```json
{
  "success": "{int_0_1}"
}
```

* GET /setup_secret

Request:
```json
```

Response:
```json
{
  "clientSecret": "{string}"
}
```

* PATCH /user

Request:
```json
{
  "paymentMethodId": "{paymentMethodId}"
}
```

Response:
```json
{
  "success": "{int_0_1}"
}
```

* DELETE /payment_methods/{payment_method_id}

Request:
```json
{
  "paymentMethodId": "{paymentMethodId}"
}
```

Response:
```json
{
  "success": "{int_0_1}"
}
```

## Webhooks

https://stripe.com/docs/api/webhook_endpoints
https://laravel.com/docs/8.x/billing#handling-stripe-webhooks
http://api.xxx.backend/stripe/webhook (see CLI usage + https://stripe.com/docs/api/events/list)

<details>
<summary>Stripe CMS</summary>

</details>

See WebHooksController - it's a proxy on a main service webhook page (env CLIENT_HOST_URL) to perform some action: increase balance, register a payment etc.

* POST /webhook

Request:
```json
{
  "payload": {
    "data": {
      "object": {
        "id": "seti_{uid}",
        "object": "{name}",
        "application": null,
        "cancellation_reason": null,
        "client_secret": "seti_{env_secret}",
        "created": 1642996106,
        "customer": "cus_{cuid}",
        "description": null,
        "metadata": {
        },
        ...
      },
      "livemode": false,
      "pending_webhooks": 0,
      "request": {
        "id": "req_{uid}",
        "idempotency_key": "{key}"
      },
      "type": "{type}"
    }
  }
}
```

Response:
```json
{
  "200": "OK"
}
```
