<?php
// app/Services/XpService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

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

    private static function col(string $table, string $name): bool
{
    return Schema::hasColumn($table, $name);
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

    // app/Services/XpService.php
public static function awardFixedXp(
    int $userId,
    string $sourceKey,
    int $amount,
    Carbon $now
): array {
    return DB::transaction(function () use ($userId, $sourceKey, $amount, $now) {

        $exists = DB::table('xp_events')
            ->where('user_id', $userId)
            ->where('attempt_id', $sourceKey)
            ->where('type', 'fixed_award')
            ->exists();

        if ($exists) {
            $user = DB::table('users')->where('user_id', $userId)->lockForUpdate()->first();
            $xp   = (int)($user->xp_total ?? 0);
            return [
                'awarded_xp' => 0,
                'xp_total'   => $xp,
                'level'      => self::levelForXp($xp),
            ];
        }

        // Lock user
        $user    = DB::table('users')->where('user_id', $userId)->lockForUpdate()->first();
        $xpTotal = (int)($user->xp_total ?? 0);

        // Build event insert with only existing columns
        $event = [
            'user_id'    => $userId,
            'attempt_id' => $sourceKey,
            'type'       => 'fixed_award',
            'xp'         => $amount,
        ];
        if (self::col('xp_events', 'meta'))       { $event['meta'] = json_encode(['reason' => 'challenge_completed']); }
        if (self::col('xp_events', 'created_at')) { $event['created_at'] = $now; }
        if (self::col('xp_events', 'updated_at')) { $event['updated_at'] = $now; }

        DB::table('xp_events')->insert($event);

        // Update user totals and level
        $xpTotal += $amount;
        $level    = self::levelForXp($xpTotal);

        $update = ['xp_total' => $xpTotal];
        if (self::col('users', 'level'))              { $update['level'] = $level; }
        if (self::col('users', 'last_xp_awarded_at')) { $update['last_xp_awarded_at'] = $now; }
        if (self::col('users', 'updated_at'))         { $update['updated_at'] = $now; }

        DB::table('users')->where('user_id', $userId)->update($update);

        return [
            'awarded_xp' => $amount,
            'xp_total'   => $xpTotal,
            'level'      => $level,
        ];
    });
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

    // Candidate awards
    $awards = [];
    if ($isOfficial) {
        $awards[] = ['type' => 'attempt_base', 'xp' => 80, 'meta' => ['weekly' => true]];

        if ($prevOfficialWeeklyKg && $prevOfficialWeeklyKg > 0) {
            $imprPct = max(0.0, (($prevOfficialWeeklyKg - $thisWeeklyKg) / $prevOfficialWeeklyKg) * 100.0);
            $bonus   = (int) round(min(120, $imprPct * 1.5));
            if ($bonus > 0) {
                $awards[] = ['type' => 'improvement_bonus', 'xp' => $bonus, 'meta' => ['improvement_pct' => round($imprPct, 1)]];
            }
        }

        // Only add the streak placeholder if streak columns exist
        if (self::col('users', 'streak_weeks') && self::col('users', 'last_official_week')) {
            $awards[] = ['type' => 'streak_bonus', 'xp' => null, 'meta' => []];
        }
    } else {
        $awards[] = ['type' => 'practice_small', 'xp' => 20, 'meta' => ['multiple_per_day' => true]];
    }

    $result = DB::transaction(function () use (
        $userId, $attemptId, $awards, $DAILY_CAP, $WEEKLY_CAP,
        $todayStart, $weekStart, $now, $isOfficial
    ) {
        // Lock user
        $user    = DB::table('users')->where('user_id', $userId)->lockForUpdate()->first();
        $xpTotal = (int)($user->xp_total ?? 0);

        // Read optional streak fields only if they exist
        $streakWeeks = self::col('users','streak_weeks') ? (int)($user->streak_weeks ?? 0) : 0;
        $lastOfficialWeek = (self::col('users','last_official_week') && !empty($user->last_official_week))
            ? Carbon::parse($user->last_official_week)
            : null;

        // Fill streak bonus if possible
        if ($isOfficial && self::col('users','streak_weeks') && self::col('users','last_official_week')) {
            $lastWeekStart = $lastOfficialWeek?->copy()->startOfWeek(Carbon::MONDAY);
            $isConsecutive = $lastWeekStart && $lastWeekStart->equalTo($now->copy()->subWeek()->startOfWeek(Carbon::MONDAY));
            $streakWeeks   = $isConsecutive ? max(1, $streakWeeks + 1) : 1;

            foreach ($awards as &$evt) {
                if ($evt['type'] === 'streak_bonus') {
                    $evt['xp']   = min(50, 10 * $streakWeeks);
                    $evt['meta'] = ['streak_weeks' => $streakWeeks];
                }
            }
            unset($evt);
        } else {
            // If we can’t compute streak, drop that placeholder
            $awards = array_values(array_filter($awards, fn($e) => $e['type'] !== 'streak_bonus'));
        }

        // Caps from xp_events (if created_at exists, filter by date; otherwise sum all)
        $xpTodayQuery = DB::table('xp_events')->where('user_id', $userId);
        if (self::col('xp_events','created_at')) {
            $xpTodayQuery->where('created_at', '>=', $todayStart);
        }
        $xpToday = (int)$xpTodayQuery->sum('xp');

        $xpThisWeekQuery = DB::table('xp_events')->where('user_id', $userId);
        if (self::col('xp_events','created_at')) {
            $xpThisWeekQuery->where('created_at', '>=', $weekStart);
        }
        $xpThisWeek = (int)$xpThisWeekQuery->sum('xp');

        $awardedTotal = 0;

        foreach ($awards as $evt) {
            // Idempotency per attempt+type
            $exists = DB::table('xp_events')
                ->where('user_id', $userId)
                ->where('attempt_id', $attemptId)
                ->where('type', $evt['type'])
                ->exists();
            if ($exists) continue;

            $remainingDay  = max(0, $DAILY_CAP  - $xpToday);
            $remainingWeek = max(0, $WEEKLY_CAP - $xpThisWeek);
            $room          = min($remainingDay, $remainingWeek);
            if ($room <= 0) break;

            $grantXp = min((int)$evt['xp'], $room);
            if ($grantXp <= 0) continue;

            // Build safe insert for xp_events
            $insert = [
                'user_id'    => $userId,
                'attempt_id' => $attemptId,
                'type'       => $evt['type'],
                'xp'         => $grantXp,
            ];
            if (self::col('xp_events','meta'))       { $insert['meta'] = json_encode($evt['meta'] ?? []); }
            if (self::col('xp_events','created_at')) { $insert['created_at'] = $now; }
            if (self::col('xp_events','updated_at')) { $insert['updated_at'] = $now; }

            DB::table('xp_events')->insert($insert);

            $xpToday    += $grantXp;
            $xpThisWeek += $grantXp;
            $xpTotal    += $grantXp;
            $awardedTotal += $grantXp;
        }

        // Persist user counters (only columns that exist)
        $level  = self::levelForXp($xpTotal);
        $update = ['xp_total' => $xpTotal];

        if (self::col('users','xp_today'))           { $update['xp_today'] = $xpToday; }
        if (self::col('users','xp_this_week'))       { $update['xp_this_week'] = $xpThisWeek; }
        if (self::col('users','level'))              { $update['level'] = $level; }
        if (self::col('users','last_xp_awarded_at')) { $update['last_xp_awarded_at'] = $now; }
        if (self::col('users','updated_at'))         { $update['updated_at'] = $now; }

        if ($isOfficial) {
            if (self::col('users','streak_weeks'))       { $update['streak_weeks'] = $streakWeeks; }
            if (self::col('users','last_official_week')) { $update['last_official_week'] = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString(); }
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
