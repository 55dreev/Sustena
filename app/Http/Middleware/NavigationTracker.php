<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NavigationTracker
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('welcome')->with('error', 'Please log in.');
        }

        $currentRoute = $request->route()->getName();
        $referer = $request->headers->get('referer');
        $previousRoute = session('previous_route', 'landing-page'); // default to landing-page

        \Log::info("⛳ NAVIGATION TRACKER:");
        \Log::info("▶ Current: {$currentRoute}");
        \Log::info("↩ Referer: {$referer}");

        // Allow internal navigation (e.g., clicking buttons)
        if ($referer && str_contains($referer, $request->getHost())) {
            session(['previous_route' => $currentRoute]);
            return $next($request);
        }

        // If no referer (user typed URL), redirect to previous_route or landing-page
        if ($currentRoute !== $previousRoute) {
            return redirect()->route($previousRoute)->with('error', 'Manual URL access is disabled.');
        }

        // Already on this page, let it pass
        return $next($request);
    }
}
