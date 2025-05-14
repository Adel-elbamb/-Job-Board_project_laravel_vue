
<?php

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    protected $routeMiddleware = [

        'restrictTo' => \App\Http\Middleware\RestrictTo::class,
    ];

}
