<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PointService
{
    public const DAILY_CAP  = 200;
    public const WEEKLY_CAP = 600;

    public static function awardAttemptPoints(
        int $userId,
        ?string $attemptId,
        string $type,
        int $basePoints,
        array $meta = []
    ): array {
        return DB::transaction(function () use ($userId, $attemptId, $type, $basePoints, $meta) {
            // idempotency
            $exists = DB::table('point_events')
                ->where('user_id', $userId)
                ->where('type', $type)
                ->when($attemptId, fn($q) => $q->where('attempt_id', $attemptId))
                ->exists();

            if ($exists) {
                return [
                    'awarded' => 0,
                    'reason' => 'duplicate',
                    'daily_used' => self::dailyUsed($userId),
                    'weekly_used'=> self::weeklyUsed($userId),
                ];
            }

            $now = Carbon::now();
            $startOfDay  = $now->copy()->startOfDay();
            $startOfWeek = $now->copy()->startOfWeek();

            $dailyUsed  = self::dailyUsed($userId, $startOfDay);
            $weeklyUsed = self::weeklyUsed($userId, $startOfWeek);

            $dailyLeft  = max(0, self::DAILY_CAP  - $dailyUsed);
            $weeklyLeft = max(0, self::WEEKLY_CAP - $weeklyUsed);
            $capLeft    = min($dailyLeft, $weeklyLeft);
            $toAward    = max(0, min($basePoints, $capLeft));

            // log event (even if 0)
            DB::table('point_events')->insert([
                'user_id'    => $userId,
                'attempt_id' => $attemptId,
                'type'       => $type,
                'points'     => $toAward,
                'meta'       => $meta ? json_encode($meta) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($toAward > 0) {
                // IMPORTANT: use user_id, not id
                DB::table('users')
                    ->where('user_id', $userId)
                    ->update([
                        'points_total' => DB::raw('points_total + '.(int)$toAward)
                    ]);
            }

            return [
                'awarded'    => $toAward,
                'reason'     => $toAward === 0 ? 'cap_reached' : 'ok',
                'daily_used' => $dailyUsed + $toAward,
                'weekly_used'=> $weeklyUsed + $toAward,
            ];
        });
    }

    protected static function dailyUsed(int $userId, ?Carbon $startOfDay = null): int {
        $startOfDay ??= Carbon::now()->startOfDay();
        return (int) DB::table('point_events')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $startOfDay)
            ->sum('points');
    }

    protected static function weeklyUsed(int $userId, ?Carbon $startOfWeek = null): int {
        $startOfWeek ??= Carbon::now()->startOfWeek();
        return (int) DB::table('point_events')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $startOfWeek)
            ->sum('points');
    }
}
