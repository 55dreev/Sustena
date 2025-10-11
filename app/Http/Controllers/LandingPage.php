<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LandingPage extends Controller
{
 // App/Http/Controllers/LandingPage.php
public function landing()
{
    $userId = Auth::id();
    $deltaPct = null;
    $deltaKg  = null;

    if ($userId) {
        $two = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->whereNotNull('kg_per_week')
            ->orderByDesc('id')
            ->limit(2)
            ->pluck('kg_per_week')
            ->values();

        if ($two->count() === 2) {
            $latest = (float) $two[0];
            $prev   = (float) $two[1];

            $deltaKg  = round($latest - $prev, 1);
            $deltaPct = $prev != 0.0 ? round((($latest - $prev) / $prev) * 100, 1) : 0.0;
        }
    }

    // Only pass primitives to the view
    return view('landing-page', [
        'delta_pct' => $deltaPct,
        'delta_kg'  => $deltaKg,
    ]);
}

}
