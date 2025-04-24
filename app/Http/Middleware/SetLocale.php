<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $locale = session('locale');
        Log::info('Current session locale: ' . ($locale ?? 'not set'));

        if ($locale && in_array($locale, ['en', 'es', 'fr', 'ar'])) {
            App::setLocale($locale);
            Log::info('Locale set to: ' . App::getLocale());
        } else {
            App::setLocale(config('app.locale'));
            Log::info('Using default locale: ' . App::getLocale());
        }

        return $next($request);
    }
}
