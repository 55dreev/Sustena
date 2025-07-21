<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next)
    {
        // Use only Laravel's auth check
        if (auth()->check()) {
            return redirect()->route('landing-page')->with('error', 'You are already logged in.');
        }

        return $next($request);
    }
}

