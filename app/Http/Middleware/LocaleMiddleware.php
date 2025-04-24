<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale', config('app.locale')); // Default to 'en' or the session locale
        App::setLocale($locale);

        return $next($request);
    }
}