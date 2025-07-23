<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestRedirect
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('username')) {
            return redirect()->route('landing-page'); // or your authenticated route
        }

        return $next($request);
    }
}
