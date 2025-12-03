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

    public function visualProgress()
    {
        $user = Auth::user();
        $uid  = $user?->user_id ?? $user?->id;

        // Get user stats
        $xp_total = (int) ($user->xp_total ?? 0);
        $level = (int) ($user->level ?? 1);
        $points_total = (int) ($user->points_total ?? 0);

        // Calculate level progress (0-100% to next level)
        $xpService = app(\App\Services\XpService::class);
        $nextLevelInfo = $xpService->nextLevelInfo($xp_total);
        // nextLevelInfo returns [current_level, next_level, xp_needed]
        $current_level = $nextLevelInfo[0];
        $next_level = $nextLevelInfo[1];
        $xp_needed = $nextLevelInfo[2];

        $currentLevelMin = $xpService->levelThresholdTotal($current_level);
        $nextLevelMin = $xpService->levelThresholdTotal($next_level);
        $xpIntoLevel = $xp_total - $currentLevelMin;
        $xpForLevel = $nextLevelMin - $currentLevelMin;
        $level_progress_percent = $xpForLevel > 0 ? min(100, ($xpIntoLevel / $xpForLevel) * 100) : 0;

        // Calculate day streak
        $streak_days = 0;
        if ($uid) {
            $appTz = config('app.timezone', 'Asia/Manila');
            $raw = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->max('created_at');

            if ($raw) {
                $lastCarbon = Carbon::parse($raw);
                $last_activity_at = $lastCarbon;
                $anchorLocal = $last_activity_at->copy()->startOfDay();

                for ($i = 0; $i < 365; $i++) {
                    $startLocal = $anchorLocal->copy()->subDays($i)->startOfDay();
                    $endLocal = $anchorLocal->copy()->subDays($i)->endOfDay();
                    $had = DB::table('footprint_scores')
                        ->where('user_id', $uid)
                        ->whereBetween('created_at', [$startLocal, $endLocal])
                        ->exists();
                    if ($had) { $streak_days++; } else { break; }
                }
            }
        }

        // Calculate week streak
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

        // Calculate composite progress score (0-100)
        // Weights: Level Progress (30%), Total XP scaled (25%), Day Streak (25%), Week Streak (20%)
        $level_score = $level_progress_percent * 0.30;

        // XP score: scale by level milestones (level 10 = 50%, level 20 = 75%, level 30+ = 100%)
        $xp_score = min(100, ($level / 30) * 100) * 0.25;

        // Day streak score: 30 days = 100%
        $day_streak_score = min(100, ($streak_days / 30) * 100) * 0.25;

        // Week streak score: 12 weeks = 100%
        $week_streak_score = min(100, ($streak_weeks / 12) * 100) * 0.20;

        $composite_score = $level_score + $xp_score + $day_streak_score + $week_streak_score;

        // Determine planet stage (0-6)
        // 0: Dying (0-10%), 1: Polluted (10-25%), 2: Healing Start (25-40%),
        // 3: Improving (40-60%), 4: Healthy (60-80%), 5: Thriving (80-95%), 6: Paradise (95-100%)
        $planet_stage = 0;
        if ($composite_score >= 95) $planet_stage = 6;
        elseif ($composite_score >= 80) $planet_stage = 5;
        elseif ($composite_score >= 60) $planet_stage = 4;
        elseif ($composite_score >= 40) $planet_stage = 3;
        elseif ($composite_score >= 25) $planet_stage = 2;
        elseif ($composite_score >= 10) $planet_stage = 1;

        $stage_names = [
            0 => 'Critical State',
            1 => 'Polluted World',
            2 => 'Healing Begins',
            3 => 'Improving',
            4 => 'Healthy Planet',
            5 => 'Thriving World',
            6 => 'Paradise Earth'
        ];

        $stage_messages = [
            0 => 'Your planet needs urgent care. Start your sustainability journey!',
            1 => 'The atmosphere is clearing. Keep going!',
            2 => 'Forests are returning. Your efforts are making a difference!',
            3 => 'Oceans are healing. The planet is responding to your actions!',
            4 => 'Wildlife is thriving. You\'re creating real change!',
            5 => 'Clean energy powers the world. You\'re a sustainability champion!',
            6 => 'Perfect harmony achieved! You\'ve created a sustainable paradise!'
        ];

        return view('visual-progress', [
            'xp_total' => $xp_total,
            'level' => $level,
            'points_total' => $points_total,
            'streak_days' => $streak_days,
            'streak_weeks' => $streak_weeks,
            'level_progress_percent' => round($level_progress_percent, 1),
            'composite_score' => round($composite_score, 1),
            'planet_stage' => $planet_stage,
            'stage_name' => $stage_names[$planet_stage],
            'stage_message' => $stage_messages[$planet_stage],
            'next_level' => $next_level,
            'xp_needed' => $xp_needed,
        ]);
    }
}
