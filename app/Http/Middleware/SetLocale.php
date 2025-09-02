<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->hasHeader('Accept-Language')) {

            session()->put('language', $request->header('Accept-Language'));
            $language = $request->header('Accept-Language');
            if (isset($language)) {
                app()->setLocale($language);
            }
            return $next($request);
        } elseif (session('language')) {
            $language = session('language');
            if (isset($language)) {
                app()->setLocale($language);
            }
            return $next($request);
        } elseif (config('environment_system.primary_language')) {
            $language = config('environment_system.primary_language');
            if (isset($language)) {
                app()->setLocale($language);
            }
            return $next($request);
        }
        return $next($request);
    }
}
