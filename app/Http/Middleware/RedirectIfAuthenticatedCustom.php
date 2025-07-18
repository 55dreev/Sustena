<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() || session()->has('username')) {
            return redirect()->route('landing-page')->with('error', 'You are already logged in.');
        }

        return $next($request);
    }
}
