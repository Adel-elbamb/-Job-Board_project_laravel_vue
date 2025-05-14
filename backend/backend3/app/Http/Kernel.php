
<?php


use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, 
            \Illuminate\Auth\Middleware\Authenticate::class, 
        ],
    ];

    protected $routeMiddleware = [
        'restrictTo' => \App\Http\Middleware\RestrictTo::class,
        'ensureUserIsEmployer' => \App\Http\Middleware\EnsureUserIsEmployer::class,
    ];
}
