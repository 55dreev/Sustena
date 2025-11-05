<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('landing-page');
    }

    public function landing()
    {
        $user = Auth::user();
        $uid  = $user?->user_id ?? $user?->id;

        // -----------------------
        // Daily streak
        // -----------------------
        $streak_days      = 0;
        $last_activity_at = null;

        if ($uid) {
            $appTz   = config('app.timezone', 'Asia/Manila');
            $dbIsUtc = false; // set true if DB is stored in UTC

            $raw = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->max('created_at');

            if ($raw) {
                $lastCarbon       = Carbon::parse($raw);
                $last_activity_at = $dbIsUtc ? $lastCarbon->tz($appTz) : $lastCarbon;

                $anchorLocal = $last_activity_at->copy()->startOfDay();
                for ($i = 0; $i < 365; $i++) {
                    $startLocal = $anchorLocal->copy()->subDays($i)->startOfDay();
                    $endLocal   = $anchorLocal->copy()->subDays($i)->endOfDay();

                    $startForDb = $dbIsUtc ? $startLocal->copy()->tz('UTC') : $startLocal;
                    $endForDb   = $dbIsUtc ? $endLocal->copy()->tz('UTC')   : $endLocal;

                    $had = DB::table('footprint_scores')
                        ->where('user_id', $uid)
                        ->whereBetween('created_at', [$startForDb, $endForDb])
                        ->exists();

                    if ($had) { $streak_days++; } else { break; }
                }
            }
        }

        // -----------------------
        // Week streak
        // -----------------------
        $streak_weeks = 0;

        if ($uid) {
            $lastWeekAny = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->max('created_at');

            if ($lastWeekAny) {
                $anchorW = Carbon::parse($lastWeekAny)->startOfWeek(Carbon::MONDAY);

                for ($w = 0; $w < 52; $w++) {
                    $ws = (clone $anchorW)->subWeeks($w);
                    $we = (clone $ws)->endOfWeek(Carbon::SUNDAY);

                    $had = DB::table('footprint_scores')
                        ->where('user_id', $uid)
                        ->whereBetween('created_at', [$ws, $we])
                        ->exists();

                    if ($had) { $streak_weeks++; } else { break; }
                }
            }
        }

        // -----------------------
        // CO2 delta (latest vs previous)
        // -----------------------
        $deltaPct = null;
        $deltaKg  = null;

        if ($uid) {
            $two = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->whereNotNull('kg_per_week')
                ->orderByDesc('id')
                ->limit(2)
                ->pluck('kg_per_week')
                ->values();

            if ($two->count() === 2) {
                $latest   = (float) $two[0];
                $prev     = (float) $two[1];
                $deltaKg  = round($latest - $prev, 1);
                $deltaPct = $prev != 0.0
                    ? round((($latest - $prev) / $prev) * 100, 1)
                    : 0.0;
            }
        }

        // -----------------------
        // Badges (compact)
        // -----------------------
        $earned = DB::table('user_badges as ub')
            ->join('badges as b', 'b.id', '=', 'ub.badge_id')
            ->where('ub.user_id', $uid)
            ->orderByDesc('ub.awarded_at')
            ->get(['b.slug','b.name','b.category','b.points_reward','ub.awarded_at']);

        $iconByCat = [
            'energy'=>'⚡','water'=>'💧','waste'=>'🧺','carbon'=>'✅',
            'trees'=>'🌳','transport'=>'🚲','home'=>'🏠','meta'=>'🏅',
        ];

        $badges = $earned->map(function ($r) use ($iconByCat) {
            $cat = strtolower($r->category ?? '');
            return [
                'icon'       => $iconByCat[$cat] ?? '🥇',
                'name'       => $r->name,
                'slug'       => $r->slug,
                'points'     => (int) $r->points_reward,
                'awarded_at' => $r->awarded_at,
            ];
        })->values()->toArray();

        // -----------------------
        // Energy change (kWh)
        // 1) Month view (start-of-month vs latest-in-month)
        // 2) Last vs previous attempt (for "up" / "saved vs last")
        // -----------------------
        $energy_saved_kwh         = 0.0; // ≥ 0, month view
        $energy_change_kwh_signed = 0.0; // can be negative (month view)

        $energy_delta_kwh_signed  = 0.0; // vs last attempt; + = saved, - = up
        $energy_delta_kwh_abs     = 0.0;
        $energy_delta_direction   = 'flat'; // 'up' | 'down' | 'flat'

        if ($uid) {
            // Treat both “energy” and “electricity” as the same category
            $aliases = ['energy', 'electricity', 'electric'];

            $base = fn() => DB::table('footprint_category_totals as ct')
                ->join('footprint_scores as fs', 'fs.attempt_id', '=', 'ct.attempt_id')
                ->where('fs.user_id', $uid)
                ->whereIn(DB::raw('LOWER(ct.category)'), $aliases);

            // --- (1) Month view ---
            $monthStart = now()->startOfMonth();
            $monthEnd   = now()->endOfMonth();

            $baseline = $base()
                ->where('fs.created_at', '<', $monthStart)
                ->orderBy('fs.created_at', 'desc')
                ->value('ct.total_score'); // kg CO2-eq

            $earliestThisMonth = $base()
                ->whereBetween('fs.created_at', [$monthStart, $monthEnd])
                ->orderBy('fs.created_at', 'asc')
                ->value('ct.total_score');

            $latestThisMonth = $base()
                ->whereBetween('fs.created_at', [$monthStart, $monthEnd])
                ->orderBy('fs.created_at', 'desc')
                ->value('ct.total_score');

            if (!is_null($latestThisMonth) && (!is_null($baseline) || !is_null($earliestThisMonth))) {
                $startKg = !is_null($baseline) ? (float) $baseline : (float) $earliestThisMonth;
                $kg_change_signed = $startKg - (float) $latestThisMonth; // + = saved, - = up
                $kg_saved         = max(0.0, $kg_change_signed);

                $kg_per_kWh = 0.70; // PH grid factor
                if ($kg_per_kWh > 0) {
                    $energy_change_kwh_signed = round($kg_change_signed / $kg_per_kWh, 1);
                    $energy_saved_kwh         = round($kg_saved        / $kg_per_kWh, 1);
                }
            }

            // --- (2) Versus previous attempt (latest two energy entries) ---
            $twoEnergy = $base()
                ->orderBy('fs.created_at', 'desc')
                ->limit(2)
                ->pluck('ct.total_score')
                ->values();

            if ($twoEnergy->count() === 2) {
                $latestKg = (float) $twoEnergy[0];
                $prevKg   = (float) $twoEnergy[1];

                // Positive => latest < prev => saved vs last
                $kg_change_vs_last = $prevKg - $latestKg;

                $kg_per_kWh = 0.70;
                if ($kg_per_kWh > 0) {
                    $energy_delta_kwh_signed = round($kg_change_vs_last / $kg_per_kWh, 1);
                    $energy_delta_kwh_abs    = abs($energy_delta_kwh_signed);

                    if ($energy_delta_kwh_signed > 0)      $energy_delta_direction = 'down'; // saved
                    elseif ($energy_delta_kwh_signed < 0) $energy_delta_direction = 'up';
                    else                                   $energy_delta_direction = 'flat';
                }
            }
        }

        // -----------------------
        // Weekly Goal (user_daily_challenges)
        // -----------------------
        $weekly_total = 0;
        $weekly_completed = 0;
        $weekly_remaining = 0;
        $weekly_percent = 0;

        if ($uid) {
            $weekStart = now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $weekEnd   = now()->endOfWeek(Carbon::SUNDAY)->toDateString();

            $weeklyBase = DB::table('user_daily_challenges')
                ->where('user_id', $uid)
                ->whereBetween('date_for', [$weekStart, $weekEnd]);

            $weekly_total = (clone $weeklyBase)->count();

            $weekly_completed = (clone $weeklyBase)
                ->where(function ($q) {
                    $q->where('status', 'completed')
                      ->orWhereNotNull('completed_at');
                })
                ->count();

            $weekly_remaining = max(0, $weekly_total - $weekly_completed);
            $weekly_percent   = $weekly_total > 0
                ? (int) round(($weekly_completed / $weekly_total) * 100)
                : 0;
        }

        // -----------------------
        // Rank (by points_total)
        // -----------------------
        $myPoints        = (int) ($user->points_total ?? 0);
        $community_total = (int) DB::table('users')->count();

        $rank_number = $community_total
            ? (DB::table('users')->where('points_total', '>', $myPoints)->count() + 1)
            : null;

        $rank_text  = $rank_number ? "#{$rank_number}" : '—';

        $percentile = $rank_number && $community_total > 0
            ? max(1, min(100, (int) round(100 * (1 - ($rank_number - 1) / max(1, $community_total)))))
            : null;

        $rank_delta = '—';

        // -----------------------
        // Single final return
        // -----------------------
        return view('landing-page', [
            // Streaks
            'streak_days'      => $streak_days,
            'streak_weeks'     => $streak_weeks,
            'last_activity_at' => $last_activity_at,

            // CO2 delta
            'deltaPct'         => $deltaPct,
            'deltaKg'          => $deltaKg,

            // Badges
            'badges'           => $badges,
            'badges_count'     => count($badges),

            // Energy (month view)
            'energy_saved_kwh'         => $energy_saved_kwh,
            'energy_change_kwh_signed' => $energy_change_kwh_signed,

            // Energy (vs last attempt)
            'energy_delta_kwh_signed'  => $energy_delta_kwh_signed, // + saved, - up
            'energy_delta_kwh_abs'     => $energy_delta_kwh_abs,
            'energy_delta_direction'   => $energy_delta_direction,  // 'up' | 'down' | 'flat'

            // Weekly Goal (totals)
            'weekly_total'       => $weekly_total,
            'weekly_completed'   => $weekly_completed,
            'weekly_remaining'   => $weekly_remaining,
            'weekly_percent'     => $weekly_percent,

            // Aliases for existing Blade (if any)
            'weekly_goal_target' => $weekly_total,
            'weekly_goal_count'  => $weekly_completed,
            'goal_percent'       => $weekly_percent,

            // Rank
            'community_total'  => $community_total,
            'rank_text'        => $rank_text,
            'rank_number'      => $rank_number,
            'rank_delta'       => $rank_delta,
            'rank_percentile'  => $percentile,
        ]);
    }
}
