<?php

// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $uid  = $user?->user_id ?? $user?->id;

        // DAILY STREAK (practice OR official)
        $streak_days = 0;
        if ($uid) {
            $last = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->max('created_at');

            if ($last) {
                $anchor = Carbon::parse($last)->startOfDay();
                for ($i = 0; $i < 365; $i++) {             // small safety cap
                    $ds = (clone $anchor)->subDays($i);
                    $de = (clone $ds)->endOfDay();

                    $had = DB::table('footprint_scores')
                        ->where('user_id', $uid)
                        ->whereBetween('created_at', [$ds, $de])
                        ->exists();

                    if ($had) { $streak_days++; } else { break; }
                }
            }
        }

        // (optional) WEEK STREAK of official attempts
        $streak_weeks = 0;
        if ($uid) {
            // anchor = week of last official attempt
            $lastWeek = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->where('is_official', 1)
                ->max('created_at');

            if ($lastWeek) {
                $anchorW = Carbon::parse($lastWeek)->startOfWeek(Carbon::MONDAY);
                for ($w = 0; $w < 52; $w++) {
                    $ws = (clone $anchorW)->subWeeks($w);
                    $we = (clone $ws)->endOfWeek(Carbon::SUNDAY);

                    $had = DB::table('footprint_scores')
                        ->where('user_id', $uid)
                        ->where('is_official', 1)
                        ->whereBetween('created_at', [$ws, $we])
                        ->exists();

                    if ($had) { $streak_weeks++; } else { break; }
                }
            }
        }
    $earned = DB::table('user_badges as ub')
        ->join('badges as b', 'b.id', '=', 'ub.badge_id')
        ->where('ub.user_id', $uid)
        ->orderByDesc('ub.awarded_at')
        ->get(['b.slug','b.name','b.category','b.points_reward','ub.awarded_at']);

    $iconByCat = [
        'energy'=>'⚡','water'=>'💧','waste'=>'🧺','carbon'=>'✅',
        'trees'=>'🌳','transport'=>'🚲','home'=>'🏠','meta'=>'🏅',
    ];

    $badges = $earned->map(function($r) use ($iconByCat){
        $cat = strtolower($r->category ?? '');
        return [
            'icon'       => $iconByCat[$cat] ?? '🥇',
            'name'       => $r->name,
            'slug'       => $r->slug,
            'points'     => (int) $r->points_reward,
            'awarded_at' => $r->awarded_at,
        ];
    })->values()->toArray();

    // Points used for ranking (adjust if you rank by a different column)
$myPoints = (int)($user->points_total ?? 0);

// Total users for context
$community_total = DB::table('users')->count();

// Dense rank: users with strictly greater points + 1
$rank = DB::table('users')->where('points_total', '>', $myPoints)->count() + 1;

// Optional: percentile (“Top X%”)
$percentile = $community_total > 0
    ? max(1, min(100, (int) round(100 * (1 - ($rank - 1) / max(1, $community_total)))))
    : null;

// If you track last rank somewhere, compute the delta (fallback to 0)
$rank_delta_num = 0;  // e.g., $user->last_rank ? $user->last_rank - $rank : 0;
$rank_delta = $rank_delta_num === 0 ? '—'
    : ($rank_delta_num > 0 ? "↗️ +{$rank_delta_num}" : "↘️ ".abs($rank_delta_num));


return view('landing-page', [
    'streak_days'   => $streak_days,
    'streak_weeks'  => $streak_weeks,
    'badges'       => $badges,
    'badges_count' => count($badges),   // ✅
    'community_total' => $community_total,
    'rank_text'       => "#{$rank}",
    'rank_number'     => $rank,
    'rank_delta'      => $rank_delta,
    'rank_percentile' => $percentile,  // Top X%
]);

    }

    private function dailyStreak(int $userId, ?Carbon $now = null): int
{
    $now = $now ?: now();
    $appTz = config('app.timezone', 'UTC');

    // latest activity (any attempt)
    $latest = DB::table('footprint_scores')
        ->where('user_id', $userId)
        ->max('created_at');

    if (!$latest) return 0;

    // anchor day in app TZ
    $anchor = Carbon::parse($latest)->tz($appTz)->startOfDay();

    $streak = 0;
    // count back day-by-day; convert bounds to UTC if DB stores UTC
    for ($i = 0; $i < 365; $i++) {
        $startLocal = $anchor->copy()->subDays($i)->startOfDay();
        $endLocal   = $anchor->copy()->subDays($i)->endOfDay();

        $startUtc = $startLocal->clone()->tz('UTC');
        $endUtc   = $endLocal->clone()->tz('UTC');

        $had = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startUtc, $endUtc])
            ->exists();

        if ($had) { $streak++; } else { break; }
    }
    return $streak;
}

public function landing()
{
    $u = auth()->user();
    $uid = $u?->user_id ?? $u?->id;

    $streak_days = $uid ? $this->dailyStreak((int)$uid) : 0;

    // populate (or keep) your other variables…
    return view('landing', compact(
        'streak_days',
        // … plus your other vars like $level, $xp_total, etc.
    ));
}

}
