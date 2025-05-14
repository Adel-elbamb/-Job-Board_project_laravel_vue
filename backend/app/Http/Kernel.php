<?php


use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \App\Http\Middleware\Cors::class,
    ];

    protected $routeMiddleware = [
        'restrictTo' => \App\Http\Middleware\RestrictTo::class,
    ];
}