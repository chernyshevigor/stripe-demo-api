<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\WebSourceMiddleware;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware' => WebSourceMiddleware::class], static function() use ($router) {
    $router->post('customers/create', 'CustomersController@create');
    $router->post('customers/update', 'CustomersController@update');
    // "internal" endpoints for backend (main-service) app
    // http://api.xxx.backend/stripe/{ENDPOINT}
});

$router->group(['middleware' => 'webSource|auth:internal'], static function() use ($router) {
    $router->post('payment_intents/retrieve', 'PaymentIntentsController@retrieve');
    $router->post('payment_intents/successed', 'PaymentIntentsController@getSuccessed');
    $router->post('payment_intents/create', 'PaymentIntentsController@create');
});

$router->group(['middleware' => AuthMiddleware::class], static function() use ($router) {
    $router->get('payment_methods', 'PaymentMethodsController@get');
    $router->patch('user', 'PaymentMethodsController@update');
    $router->delete('payment_methods/{paymentMethodId}', 'PaymentMethodsController@delete');
    $router->patch('payment_secret/{paymentIntentId}', 'PaymentIntentsController@update');
    $router->get('setup_secret', 'SecretController@setup');
});

$router->post('webhook', 'WebHooksController@handleWebhook');

if (env('APP_DEBUG') === true) {
    $router->group(['middleware' => 'webSource'], static function() use ($router) {
        $router->get('customers/create', 'CustomersController@create');
        $router->get('customers/update', 'CustomersController@update');
    });
    $router->group(['middleware' => 'webSource|auth:internal'], static function() use ($router) {
        $router->get('payment_intents/retrieve', 'PaymentIntentsController@retrieve');
        $router->get('payment_intents/successed', 'PaymentIntentsController@getSuccessed');
        $router->get('payment_intents/create', 'PaymentIntentsController@create');
    });
    $router->get('webhook', 'WebHooksController@handleWebhook');
}

$router->get('/', function () {
    header('Forbidden', false, 403);
    return 'Forbidden';
});
