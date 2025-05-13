<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsEmployer
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['error' => 'Access denied. Only employers allowed.'], 403);
        }

        return $next($request);
    }
}

