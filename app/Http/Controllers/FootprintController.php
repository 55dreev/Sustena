<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Services\XpService;
use App\Services\PointService;

class FootprintController extends Controller
{
    private function toWeekly(float $value, string $basis): float
    {
        return match ($basis) {
            'daily'   => $value * 7,
            'weekly'  => $value,
            'monthly' => $value / 4.345,
            'yearly'  => $value / 52.1429,
            default   => $value,
        };
    }

    private function xpFeatureAvailable(): bool
    {
        try {
            return Schema::hasTable('xp_events')
                && Schema::hasColumn('users', 'xp_total')
                && Schema::hasColumn('users', 'level')
                && Schema::hasColumn('users', 'last_xp_awarded_at')
                && Schema::hasColumn('users', 'xp_today')
                && Schema::hasColumn('users', 'xp_this_week');
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function usersHasUpdatedAt(): bool
    {
        try {
            return Schema::hasColumn('users', 'updated_at');
        } catch (\Throwable $e) {
            return false;
        }
    }

    private static function levelThresholdTotal(int $level): int
    {
        if ($level <= 1) return 0;
        $sum = 0;
        for ($k = 1; $k < $level; $k++) {
            $sum += (int) round(100 * pow($k, 1.15));
        }
        return $sum;
    }

    private static function levelForXp(int $xpTotal): int
    {
        $lvl = 1;
        while ($xpTotal >= self::levelThresholdTotal($lvl + 1)) {
            $lvl++;
            if ($lvl > 200) break;
        }
        return $lvl;
    }

    /**
     * Fallback XP awarder for schemas without users.updated_at.
     * PRACTICE: multiple awards per day (one per attempt) under daily cap.
     * OFFICIAL: base + improvement + streak (capped daily).
     */
    private function awardAttemptXpCompatNoUpdatedAt(
        int $userId,
        string $attemptId,
        bool $isOfficial,
        float $thisWeeklyKg,
        ?float $prevOfficialWeeklyKg,
        Carbon $now
    ): array {
        if (!$this->xpFeatureAvailable()) {
            return ['awarded' => 0, 'reason' => 'xp_disabled'];
        }

        $DAILY_CAP = 200;

        $user = DB::table('users')->lockForUpdate()->where('user_id', $userId)->first();
        if (!$user) return ['awarded' => 0, 'reason' => 'no_user'];

        $todayStart = (clone $now)->startOfDay();
        $weekStart  = (clone $now)->startOfWeek(Carbon::MONDAY);

        $xpToday = (int) DB::table('xp_events')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $todayStart)
            ->sum('xp');

        if (!$isOfficial) {
            // Practice: MULTIPLE per day allowed (idempotent per attempt), under daily cap
            $practiceXp = max(10, min(15, (int) round(15 - ($thisWeeklyKg / 200))));
            $available  = max(0, $DAILY_CAP - $xpToday);
            $grant      = min($practiceXp, $available);
            if ($grant <= 0) {
                return [
                    'awarded'  => 0,
                    'reason'   => 'daily_cap',
                    'level'    => (int)($user->level ?? 1),
                    'total_xp' => (int)($user->xp_total ?? 0),
                ];
            }

            // Idempotent per attempt/type via unique (user_id, attempt_id, type)
            $exists = DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('attempt_id', $attemptId)
                ->where('type', 'practice_small')
                ->exists();
            if (!$exists) {
                DB::table('xp_events')->insert([
                    'user_id'    => $userId,
                    'attempt_id' => $attemptId,
                    'type'       => 'practice_small',
                    'xp'         => $grant,
                    'meta'       => json_encode(['multi_per_day' => true]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // recompute counters (from events) to avoid drift
            $xpTodayNew = (int) DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('created_at', '>=', $todayStart)
                ->sum('xp');

            $xpWeekNew = (int) DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('created_at', '>=', $weekStart)
                ->sum('xp');

            $newTotal = (int)($user->xp_total ?? 0) + $grant;
            $newLevel = self::levelForXp($newTotal);

            // IMPORTANT: do NOT touch updated_at (schema may not have it)
            DB::table('users')->where('user_id', $userId)->update([
                'xp_total'           => $newTotal,
                'xp_today'           => $xpTodayNew,
                'xp_this_week'       => $xpWeekNew,
                'level'              => $newLevel,
                'last_xp_awarded_at' => $now,
            ]);

            return [
                'awarded'   => $grant,
                'reason'    => 'practice_ok',
                'level'     => $newLevel,
                'total_xp'  => $newTotal,
                'breakdown' => ['practice_small' => $grant],
            ];
        }

        // OFFICIAL
        $base = 45 + (int)round(min(15, max(0, 60 - ($thisWeeklyKg / 10))));
        $base = max(35, min(60, $base));

        $improvement = 0;
        if ($prevOfficialWeeklyKg && $prevOfficialWeeklyKg > 0) {
            $chg = (($prevOfficialWeeklyKg - $thisWeeklyKg) / $prevOfficialWeeklyKg) * 100;
            if ($chg > 0) $improvement = (int) round(min(60, $chg * 2));
        }

        // streak: check prior consecutive official weeks (max +50)
        $streakBonus = 0; $streak = 0;
        for ($i = 1; $i <= 5; $i++) {
            $s = (clone $now)->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $e = (clone $s)->endOfWeek(Carbon::SUNDAY);
            $had = DB::table('footprint_scores')
                ->where('user_id', $userId)
                ->where('is_official', 1)
                ->whereBetween('created_at', [$s, $e])
                ->exists();
            if ($had) $streak++; else break;
        }
        if ($streak > 0) $streakBonus = min(50, $streak * 10);

        $raw = $base + $improvement + $streakBonus;

        $available = max(0, $DAILY_CAP - $xpToday);
        $grantTotal = min($raw, $available);
        if ($grantTotal <= 0) {
            return [
                'awarded'  => 0,
                'reason'   => 'daily_cap',
                'level'    => (int)($user->level ?? 1),
                'total_xp' => (int)($user->xp_total ?? 0),
            ];
        }

        // Log granular components (respecting daily cap distribution order)
        $toLog = [
            ['type' => 'attempt_base',      'xp' => $base],
            ['type' => 'improvement_bonus', 'xp' => $improvement],
            ['type' => 'streak_bonus',      'xp' => $streakBonus],
        ];

        $remaining = $grantTotal;
        foreach ($toLog as $piece) {
            if ($piece['xp'] <= 0 || $remaining <= 0) continue;
            $give = min($piece['xp'], $remaining);
            DB::table('xp_events')->insert([
                'user_id'    => $userId,
                'attempt_id' => $attemptId,
                'type'       => $piece['type'],
                'xp'         => $give,
                'meta'       => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $remaining -= $give;
        }

        $xpTodayNew = (int) DB::table('xp_events')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $todayStart)
            ->sum('xp');

        $xpWeekNew = (int) DB::table('xp_events')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $weekStart)
            ->sum('xp');

        $newTotal = (int)($user->xp_total ?? 0) + $grantTotal;
        $newLevel = self::levelForXp($newTotal);

        // do NOT set updated_at
        DB::table('users')->where('user_id', $userId)->update([
            'xp_total'           => $newTotal,
            'xp_today'           => $xpTodayNew,
            'xp_this_week'       => $xpWeekNew,
            'level'              => $newLevel,
            'last_xp_awarded_at' => $now,
        ]);

        return [
            'awarded'   => $grantTotal,
            'reason'    => 'ok',
            'level'     => $newLevel,
            'total_xp'  => $newTotal,
            'breakdown' => [
                'base'        => $base,
                'improvement' => $improvement,
                'streak'      => $streakBonus,
                'raw'         => $raw,
                'capped_today'=> $grantTotal,
            ],
        ];
    }

    /**
     * Save one attempt (per-category + optional overall) and mark is_official.
     * Also awards XP; if users.updated_at is missing, we fall back to a compatible awarder.
     */
    public function saveCategoryTotals(Request $request)
    {
        $data = $request->validate([
            'attempt_id'   => 'required|string|max:64',
            'totals'       => 'required|array',
            'counts'       => 'nullable|array',
            'overall'      => 'nullable|numeric',
            'basis'        => 'nullable|string|in:daily,weekly,monthly,yearly',
            'timeframe'    => 'nullable|string|in:daily,weekly,monthly,yearly,custom',
            'period_start' => 'nullable|date',
            'period_end'   => 'nullable|date|after_or_equal:period_start',
        ]);

        $u = Auth::guard('web')->user();
        $userId = $u?->user_id ?? $u?->id;
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $attemptId = $data['attempt_id'];
        $totals    = $data['totals'] ?? [];
        $counts    = $data['counts'] ?? [];
        $overall   = array_key_exists('overall', $data) ? (float)$data['overall'] : null;

        $basis       = $data['basis']      ?? 'weekly';
        $timeframe   = $data['timeframe']  ?? 'weekly';
        $periodStart = !empty($data['period_start']) ? Carbon::parse($data['period_start']) : null;
        $periodEnd   = !empty($data['period_end'])   ? Carbon::parse($data['period_end'])   : null;

        // ---- Decide official/practice ----
        $now         = Carbon::now(config('app.timezone'));
        $startOfWeek = (clone $now)->startOfWeek(Carbon::MONDAY);

        $alreadyOfficialThisWeek = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $startOfWeek)
            ->where('is_official', 1)
            ->exists();

        $reason = null;
        if (!$alreadyOfficialThisWeek) {
            $isOfficial = 1;
            $reason = 'first_official_this_week';
        } else {
            $isOfficial = 0;
            $reason = 'additional_attempt_this_week';

            $last = DB::table('footprint_scores')
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->first();

            if ($last && $last->attempt_id !== $attemptId) {
                if ($now->diffInMinutes(Carbon::parse($last->created_at)) < 10) {
                    $reason = 'anti_spam_recent';
                } elseif ($overall !== null && $last->total_score > 0) {
                    $pctChange = abs(($overall - $last->total_score) / $last->total_score) * 100;
                    if ($pctChange < 3) {
                        $reason = 'anti_spam_small_change';
                    }
                }
            }
        }

        // Compute canonical weekly for this attempt
        if (!is_null($overall)) {
            $thisWeekly = $this->toWeekly($overall, $basis);
        } else {
            $sumPosted = 0.0;
            foreach ($totals as $c => $v) $sumPosted += (float)$v;
            $thisWeekly = $this->toWeekly($sumPosted, $basis);
        }

        // Previous official for improvement bonus
        $prevOfficial = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->where('is_official', 1)
            ->orderByDesc('created_at')
            ->first();
        $prevOfficialWeekly = $prevOfficial && $prevOfficial->attempt_id !== $attemptId
            ? (float)($prevOfficial->kg_per_week ?? 0)
            : null;

        // ---- Persist atomically ----
        DB::transaction(function () use (
            $userId, $attemptId, $isOfficial, $totals, $counts, $overall, $now,
            $basis, $timeframe, $periodStart, $periodEnd
        ) {
            DB::table('footprint_scores')->upsert(
                [[
                    'user_id'      => $userId,
                    'attempt_id'   => $attemptId,
                    'total_score'  => round((float)($overall ?? 0), 2),
                    'kg_per_week'  => round((float)($overall ?? 0), 2), // patched below
                    'is_official'  => (int)$isOfficial,
                    'basis'        => $basis,
                    'timeframe'    => $timeframe,
                    'period_start' => $periodStart,
                    'period_end'   => $periodEnd,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]],
                ['user_id', 'attempt_id'],
                ['total_score','kg_per_week','is_official','basis','timeframe','period_start','period_end','updated_at']
            );

            if (!is_null($overall)) {
                DB::table('footprint_scores')
                    ->where('user_id', $userId)
                    ->where('attempt_id', $attemptId)
                    ->update(['kg_per_week' => round($this->toWeekly($overall, $basis), 3)]);
            }

            if (!empty($totals)) {
                $rows = [];
                foreach ($totals as $category => $sum) {
                    $weekly = $this->toWeekly((float)$sum, $basis);
                    $rows[] = [
                        'user_id'       => $userId,
                        'attempt_id'    => $attemptId,
                        'category'      => (string)$category,
                        'total_score'   => round((float)$sum, 2),
                        'kg_per_week'   => round($weekly, 3),
                        'answers_count' => (int)($counts[$category] ?? 0),
                        'is_official'   => (int)$isOfficial,
                        'basis'         => $basis,
                        'timeframe'     => $timeframe,
                        'period_start'  => $periodStart,
                        'period_end'    => $periodEnd,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
                DB::table('footprint_category_totals')->upsert(
                    $rows,
                    ['user_id', 'attempt_id', 'category'],
                    ['total_score','kg_per_week','answers_count','is_official','basis','timeframe','period_start','period_end','updated_at']
                );
            }
        });

        // ---- Award XP (use service if we can safely write updated_at; otherwise fallback) ----
        $xp = ['awarded' => 0];
        if ($this->xpFeatureAvailable()) {
            try {
                if ($this->usersHasUpdatedAt()) {
                    // happy path: use your service
                    $xp = XpService::awardAttemptXp(
                        (int)$userId,
                        (string)$attemptId,
                        (bool)$isOfficial,
                        (float)$thisWeekly,
                        $prevOfficialWeekly,
                        $now
                    );
                } else {
                    // compatible path: no updated_at field in users
                    $xp = $this->awardAttemptXpCompatNoUpdatedAt(
                        (int)$userId,
                        (string)$attemptId,
                        (bool)$isOfficial,
                        (float)$thisWeekly,
                        $prevOfficialWeekly,
                        $now
                    );
                }
            } catch (\Throwable $e) {
                // don't fail the save if XP blows up
                $xp = ['awarded' => 0, 'reason' => 'xp_error', 'error' => $e->getMessage()];
            }
        }

        // ---- Award POINTS (separate from XP) ----
        $points = ['awarded' => 0, 'reason' => 'points_disabled'];
        try {
            // Example rule: +100 base, +50 if improved vs last official
            $basePoints = 100;

            $improved = null;
            if (!is_null($prevOfficialWeekly) && $prevOfficialWeekly > 0) {
                $improved = ($thisWeekly < $prevOfficialWeekly);
            }
            $bonus = ($improved === true) ? 50 : 0;

            $pointsInfo = PointService::awardAttemptPoints(
                (int)$userId,
                (string)$attemptId,
                'footprint_quiz',
                $basePoints + $bonus,
                [
                    'weekly_kg' => round((float)$thisWeekly, 3),
                    'basis'     => $basis,
                    'timeframe' => $timeframe,
                    'improved'  => $improved,
                    'official'  => (bool)$isOfficial,
                ]
            );

            $points = [
                'awarded'     => (int)($pointsInfo['awarded'] ?? 0),
                'reason'      => $pointsInfo['reason'] ?? 'ok',
                'daily_used'  => $pointsInfo['daily_used'] ?? null,
                'weekly_used' => $pointsInfo['weekly_used'] ?? null,
            ];
        } catch (\Throwable $e) {
            \Log::warning('Points award failed: '.$e->getMessage());
            $points = ['awarded' => 0, 'reason' => 'points_error', 'error' => $e->getMessage()];
        }

        return response()->json([
            'status'       => 'ok',
            'attempt_id'   => $attemptId,
            'is_official'  => (bool)$isOfficial,
            'reason'       => $reason,
            'weekly_kg'    => round((float)$thisWeekly, 3),
            'xp'           => $xp,
            'points'       => $points,
        ]);
    }

    /**
     * Practice-only: save overall (no XP).
     */
    public function saveOverall(Request $request)
    {
        $data = $request->validate([
            'attempt_id'  => 'required|string|max:64',
            'total_score' => 'required|numeric',
            'basis'       => 'nullable|string|in:daily,weekly,monthly,yearly',
            'timeframe'   => 'nullable|string|in:daily,weekly,monthly,yearly,custom',
            'period_start'=> 'nullable|date',
            'period_end'  => 'nullable|date|after_or_equal:period_start',
        ]);

        $u = Auth::guard('web')->user();
        $userId = $u?->user_id ?? $u?->id;
        if (!$userId) return back()->with('error', 'Unauthenticated');

        $attemptId   = $data['attempt_id'];
        $totalScore  = (float)$data['total_score'];
        $basis       = $data['basis']      ?? 'weekly';
        $timeframe   = $data['timeframe']  ?? 'weekly';
        $periodStart = !empty($data['period_start']) ? Carbon::parse($data['period_start']) : null;
        $periodEnd   = !empty($data['period_end'])   ? Carbon::parse($data['period_end'])   : null;

        $now = Carbon::now(config('app.timezone'));

        DB::table('footprint_scores')->upsert(
            [[
                'user_id'      => $userId,
                'attempt_id'   => $attemptId,
                'total_score'  => round($totalScore, 2),
                'kg_per_week'  => round($this->toWeekly($totalScore, $basis), 3),
                'is_official'  => 0,
                'basis'        => $basis,
                'timeframe'    => $timeframe,
                'period_start' => $periodStart,
                'period_end'   => $periodEnd,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]],
            ['user_id', 'attempt_id'],
            ['total_score','kg_per_week','is_official','basis','timeframe','period_start','period_end','updated_at']
        );

        return back()->with('status', 'Saved');
    }
}
