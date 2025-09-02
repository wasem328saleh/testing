<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkActive
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $is_active=Auth::user()->employee()->first()->is_active;
        if (!$is_active)
        {
            return $this->returnError(100,"Sorry dear, your account has been locked by the administration. Please contact the manager.");
        }
        return $next($request);
    }
}
