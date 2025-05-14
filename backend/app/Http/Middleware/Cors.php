<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->header('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, Accept')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
