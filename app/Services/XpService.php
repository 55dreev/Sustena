<?php
// app/Services/XpService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class XpService
{
    // ----- Level curve -----
    public static function levelThresholdTotal(int $level): int
    {
        if ($level <= 1) return 0;
        $sum = 0;
        for ($k = 1; $k < $level; $k++) {
            $sum += (int) round(100 * pow($k, 1.15));
        }
        return $sum;
    }

    public static function levelForXp(int $xpTotal): int
    {
        $lvl = 1;
        while ($xpTotal >= self::levelThresholdTotal($lvl + 1)) {
            $lvl++;
            if ($lvl > 200) break;
        }
        return $lvl;
    }

    public static function nextLevelInfo(int $xpTotal): array
    {
        $level = self::levelForXp($xpTotal);
        $next  = $level + 1;
        $needForNext = max(0, self::levelThresholdTotal($next) - $xpTotal);
        return [$level, $next, $needForNext];
    }

    /**
     * Award XP for an attempt with anti-abuse:
     *  - Official: base + improvement + streak
     *  - Practice: award per attempt (no per-day limit), still capped by daily/weekly totals
     *  - Daily/weekly caps (from xp_events truth source)
     *  - Idempotent per (user, attempt, type)
     */
    public static function awardAttemptXp(
        int $userId,
        string $attemptId,
        bool $isOfficial,
        float $thisWeeklyKg,
        ?float $prevOfficialWeeklyKg,
        Carbon $attemptCreatedAt
    ): array {
        $tz = config('app.timezone');
        $now = $attemptCreatedAt->copy()->timezone($tz);
        $todayStart = $now->copy()->startOfDay();
        $weekStart  = $now->copy()->startOfWeek(Carbon::MONDAY);

        // Caps
        $DAILY_CAP  = 200;
        $WEEKLY_CAP = 600;

        // Prepare award candidates
        $awards = [];

        if ($isOfficial) {
            // Base (weekly official)
            $awards[] = ['type' => 'attempt_base', 'xp' => 80, 'meta' => ['weekly'=>true]];

            // Improvement bonus
            if ($prevOfficialWeeklyKg && $prevOfficialWeeklyKg > 0) {
                $imprPct = max(0.0, (($prevOfficialWeeklyKg - $thisWeeklyKg) / $prevOfficialWeeklyKg) * 100.0);
                $bonus = (int) round(min(120, $imprPct * 1.5));
                if ($bonus > 0) {
                    $awards[] = ['type' => 'improvement_bonus', 'xp' => $bonus, 'meta' => ['improvement_pct'=>round($imprPct,1)]];
                }
            }

            // Streak (consecutive official weeks) — computed after locking user row
            $awards[] = ['type' => 'streak_bonus', 'xp' => null, 'meta' => []]; // placeholder
        } else {
            // Practice: allow multiple awards per day (per unique attempt), still capped by daily/weekly totals.
            // Keep the event type stable so caps are easy to sum.
            $awards[] = ['type' => 'practice_small', 'xp' => 20, 'meta' => ['multiple_per_day'=>true]];
        }

        // ---- Transaction ensures forUpdate lock and atomic counters ----
        $result = DB::transaction(function () use (
            $userId, $attemptId, $awards, $DAILY_CAP, $WEEKLY_CAP,
            $todayStart, $weekStart, $now, $isOfficial
        ) {
            // Lock user row
            $user = DB::table('users')->where('user_id', $userId)->lockForUpdate()->first();
            $xpTotal = (int)($user->xp_total ?? 0);
            $streakWeeks = (int)($user->streak_weeks ?? 0);
            $lastOfficialWeek = $user->last_official_week ? Carbon::parse($user->last_official_week) : null;

            // Fill streak bonus value if needed
            if ($isOfficial) {
                $lastWeekStart = $lastOfficialWeek?->copy()->startOfWeek(Carbon::MONDAY);
                $isConsecutive = $lastWeekStart && $lastWeekStart->equalTo($now->copy()->subWeek()->startOfWeek(Carbon::MONDAY));
                $streakWeeks = $isConsecutive ? max(1, $streakWeeks + 1) : 1;

                foreach ($awards as &$evt) {
                    if ($evt['type'] === 'streak_bonus') {
                        $evt['xp'] = min(50, 10 * $streakWeeks);
                        $evt['meta'] = ['streak_weeks' => $streakWeeks];
                    }
                }
                unset($evt);
            }

            // Fresh caps from events (truth source)
            $xpToday = (int) DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('created_at', '>=', $todayStart)
                ->sum('xp');

            $xpThisWeek = (int) DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('created_at', '>=', $weekStart)
                ->sum('xp');

            $awardedTotal = 0;

            foreach ($awards as $evt) {
                // Idempotency per attempt+type (prevents double-awarding the same attempt)
                $exists = DB::table('xp_events')
                    ->where('user_id', $userId)
                    ->where('attempt_id', $attemptId)
                    ->where('type', $evt['type'])
                    ->exists();
                if ($exists) continue;

                $remainingDay  = max(0, $DAILY_CAP  - $xpToday);
                $remainingWeek = max(0, $WEEKLY_CAP - $xpThisWeek);
                $room = min($remainingDay, $remainingWeek);
                if ($room <= 0) break;

                $grantXp = min((int)$evt['xp'], $room);
                if ($grantXp <= 0) continue;

                DB::table('xp_events')->insert([
                    'user_id'    => $userId,
                    'attempt_id' => $attemptId,
                    'type'       => $evt['type'],
                    'xp'         => $grantXp,
                    'meta'       => json_encode($evt['meta'] ?? []),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $xpToday    += $grantXp;
                $xpThisWeek += $grantXp;
                $xpTotal    += $grantXp;
                $awardedTotal += $grantXp;
            }

            // Persist user counters + streak + level
            $level = self::levelForXp($xpTotal);
            $update = [
                'xp_total'           => $xpTotal,
                'xp_today'           => $xpToday,
                'xp_this_week'       => $xpThisWeek,
                'level'              => $level,
                'last_xp_awarded_at' => $now,
                'updated_at'         => $now,
            ];
            if ($isOfficial) {
                $update['streak_weeks']       = $streakWeeks;
                $update['last_official_week'] = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            }

            DB::table('users')->where('user_id', $userId)->update($update);

            return [$awardedTotal, $xpTotal, $level];
        });

        [$awarded, $xpTotal, $level] = $result;
        [$lvl, $next, $need] = self::nextLevelInfo($xpTotal);

        return [
            'awarded_xp' => $awarded,
            'xp_total'   => $xpTotal,
            'level'      => $level,
            'next_level' => $next,
            'xp_to_next' => $need,
        ];
    }
}
