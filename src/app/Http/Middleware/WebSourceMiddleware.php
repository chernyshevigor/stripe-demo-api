<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebSourceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (str_contains((string) $request->server->get('HTTP_X_REQUEST_SOURCE'), 'external')) {
            return response()->json([
                'success' => 0,
                'message' => 'Forbidden',
            ], 403);
        }
        return $next($request);
    }
}
