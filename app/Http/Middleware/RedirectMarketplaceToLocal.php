<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectMarketplaceToLocal
{
    public function handle(Request $request, Closure $next)
    {
        // Keep installation self-hosted: never show the external Apps marketplace.
        if ($request->routeIs('apps.*')) {
            return redirect()->route('dashboard')->with('warning', trans_choice('general.modules', 2) . ' marketplace is disabled in self-hosted mode.');
        }

        return $next($request);
    }
}
