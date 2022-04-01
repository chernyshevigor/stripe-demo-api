<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $exception)
    {
        if (config('i.artisan') == 1) {
            //parent::report($exception);
        } else {
            error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        }
        //parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => 0,
                'message' => 'Method is not allowed',
            ], 405);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => 0,
                'message' => 'Not found',
            ], 404);
        }
        if (env('APP_DEBUG') === true) {
            return parent::render($request, $exception);
        } else {
            trigger_error(
                $exception->getMessage() . PHP_EOL . $exception->getTraceAsString(), E_USER_WARNING);
            return response()->json([
                'success' => 0,
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
