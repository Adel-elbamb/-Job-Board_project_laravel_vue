<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request; // تأكدي من وجود هذا السطر

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) { // إذا لم يكن الطلب يتوقع JSON (مثل طلبات الويب العادية)
            return route('login');    // قم بالإعادة إلى مسار تسجيل الدخول المسمى
        }
        // لطلبات API (التي تتوقع JSON)، لا تقم بإرجاع أي شيء هنا.
        // سيؤدي هذا إلى رمي AuthenticationException، الذي سيتم معالجته بواسطة Handler.php
        return null;
    }
}
