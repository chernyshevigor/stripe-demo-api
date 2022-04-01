<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if ($request->user()) {
            return $next($request);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Invalid token.',
        ], 401);
    }
}
