<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('AppTokenClient', function() {
            return new AppTokenClient(env('APP_KEY'), env('TOKEN_MNG_URI'));
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot(): void
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $psrHttpFactory = new PsrHttpFactory(
                new ServerRequestFactory(), new StreamFactory(), new UploadedFileFactory(), new ResponseFactory()
            );
            /** @var AppTokenClient $tokenClient */
            $tokenClient = $this->app->make('AppTokenClient');
            $jwtToken = $request->bearerToken();
            if ($tokenClient->verifyByRequest($psrHttpFactory->createRequest($request), $jwtToken)) {
                $userId = $tokenClient->getPayload($jwtToken)['owner_id'];
                if ($userId) {
                    return User::where('app_user_id', $userId)->firstOrFail();
                }
            }
            return null;
        });

        $this->app['auth']->viaRequest('internal', function (Request $request) {
            return User::where('app_user_id', (int) $request->get('app_user_id'))->firstOrFail();
        });
    }
}

class AppTokenClient
{
    // it has to be you implementation of Bearer authentication
    // Lcobucci\JWT for instance
    // https://laravel.com/docs/8.x/authentication#laravels-api-authentication-services for instance
    #[ArrayShape(['owner_id' => "int"])] public function getPayload(string $jwtToken): array
    {
        return ['owner_id' => 0];
    }
}
