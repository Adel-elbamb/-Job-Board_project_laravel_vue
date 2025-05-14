<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictTo
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->hasRole($roles)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'You do not have permission to perform this action',
            ], 403);
        }

        return $next($request);
    }
}
