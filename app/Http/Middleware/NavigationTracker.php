<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NavigationTracker
{
    // Define the allowed navigation flow
    private $allowedFlow = [
        'welcome' => ['landing-page'],
        'landing-page' => ['landing-page','challenge', 'footprint-calculator', 'forum', 'learning-modules', 'profile'],
        'challenge' => ['landing-page', 'footprint-calculator', 'forum', 'learning-modules', 'profile'],
        'footprint-calculator' => ['landing-page', 'challenge', 'forum', 'learning-modules', 'profile'],
        'forum' => ['landing-page', 'challenge', 'footprint-calculator', 'learning-modules', 'profile'],
        'learning-modules' => ['landing-page', 'challenge', 'footprint-calculator', 'forum', 'profile'],
        'profile' => ['landing-page', 'challenge', 'footprint-calculator', 'forum', 'learning-modules'],
    ];

    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!session()->has('username')) {
            return redirect()->route('welcome')->with('error', 'Please log in to access this page.');
        }

        $currentRoute = $request->route()->getName();
        $previousRoute = session('previous_route');

        // Allow direct access to landing page after login
        if ($currentRoute === 'landing-page' && !$previousRoute) {
            session(['previous_route' => $currentRoute]);
            return $next($request);
        }

        // Check if the navigation is allowed
        if ($previousRoute && isset($this->allowedFlow[$previousRoute]) && in_array($currentRoute, $this->allowedFlow[$previousRoute])) {
            session(['previous_route' => $currentRoute]);
            return $next($request);
        }

        // If navigation is not allowed, redirect to landing page
        return redirect()->route('landing-page')->with('error', 'Invalid navigation. Please use the menu.');
    }
}