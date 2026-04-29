<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNoApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Allow requests to proceed without API key requirement
        return $next($request);
    }
}
