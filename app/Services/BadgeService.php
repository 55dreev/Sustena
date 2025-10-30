<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class BadgeService
{
    public function evaluateAttempt(int $userId, string $attemptId, bool $includePractice = true): array
    {
        $awarded = [];
        $points  = 0;

        // ------ Load attempt data ------
        $score = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->where('attempt_id', $attemptId)
            ->first();

        if (!$score) {
            Log::warning("⚠️ No footprint score found for user {$userId} and attempt {$attemptId}");
            return ['badges' => [], 'points' => 0];
        }

        $weekly = (float)($score->kg_per_week ?? $score->total_score ?? 0);

        // Load category totals
        $cats = DB::table('footprint_category_totals')
            ->where('user_id', $userId)
            ->where('attempt_id', $attemptId)
            ->get(['category', 'kg_per_week', 'total_score']);

        $nowCats = collect();
        foreach ($cats as $r) {
            $nowCats[$r->category] = (float)($r->kg_per_week ?? $r->total_score ?? 0);
        }

        // ------ Previous attempt ------
        $prevAttempt = DB::table('footprint_category_totals')
            ->select('attempt_id', DB::raw('MAX(created_at) as done'))
            ->where('user_id', $userId)
            ->where('attempt_id', '<>', $attemptId)
            ->when(!$includePractice, fn($q) => $q->where('is_official', 1))
            ->groupBy('attempt_id')
            ->orderByDesc(DB::raw('MAX(created_at)'))
            ->first();

        $prevCats = collect();
        $prevWeekly = null;

        if ($prevAttempt) {
            $prevCatsRows = DB::table('footprint_category_totals')
                ->where('user_id', $userId)
                ->where('attempt_id', $prevAttempt->attempt_id)
                ->get(['category', 'kg_per_week', 'total_score']);

            foreach ($prevCatsRows as $r) {
                $prevCats[$r->category] = (float)($r->kg_per_week ?? $r->total_score ?? 0);
            }

            $prevScore = DB::table('footprint_scores')
                ->where('user_id', $userId)
                ->where('attempt_id', $prevAttempt->attempt_id)
                ->first();

            if ($prevScore) {
                $prevWeekly = (float)($prevScore->kg_per_week ?? $prevScore->total_score ?? 0);
            }
        }

        // ------------- STATIC RULES -------------
        if ($weekly > 0 && $weekly < 100) {
            $points += $this->awardOnce($userId, 'carbon-under-100', ['weekly' => $weekly], $awarded);
        }

        if ($prevCats->isNotEmpty()) {
            $prevWaste = $prevCats['Waste Management'] ?? 0;
            $nowWaste  = $nowCats['Waste Management'] ?? 0;
            if ($prevWaste > 0) {
                $dropPct = (($prevWaste - $nowWaste) / $prevWaste) * 100;
                if ($dropPct >= 10) {
                    $points += $this->awardOnce($userId, 'waste-reducer-silver', [
                        'prev' => $prevWaste,
                        'now' => $nowWaste,
                        'drop_pct' => round($dropPct, 1)
                    ], $awarded);
                }
            }
        }

        // Load user safely
        $pk = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';
        $user = DB::table('users')->where($pk, $userId)->first();

        if (!$user) {
            Log::warning("⚠️ BadgeService: No user found for ID {$userId} using key {$pk}");
            return ['badges' => $awarded, 'points' => $points];
        }

        $level = (int)($user->level ?? 1);
        if ($level >= 10) {
            $points += $this->awardOnce($userId, 'level-10', ['level' => $level], $awarded);
        }

        // ------------- DYNAMIC RULES (from badges table) -------------
        $dynamicBadges = DB::table('badges')->get();
        foreach ($dynamicBadges as $badge) {
            if (empty($badge->rule) || $badge->rule === '[]') continue;

            $rule = json_decode($badge->rule, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($rule)) {
                Log::warning("⚠️ Invalid JSON rule for badge {$badge->slug}");
                continue;
            }

            $fact  = $rule['fact']  ?? null;
            $op    = $rule['op']    ?? null;
            $value = $rule['value'] ?? null;

            if (!$fact || !$op || $value === null) continue;

            $userValue = match ($fact) {
                'weekly_kg'          => $weekly,
                'missions_completed' => $user->missions_completed ?? 0,
                'login_days'         => $user->login_days ?? 0,
                default              => $nowCats[$fact] ?? null,
            };

            if ($userValue === null) continue;

            if ($this->compare($userValue, $value, $op)) {
                $points += $this->awardOnce($userId, $badge->slug, [
                    'fact' => $fact,
                    'op' => $op,
                    'value' => $value,
                    'user_value' => $userValue,
                ], $awarded);
            }
        }

        // ✅ Return full badge info so frontend gets name, icon, and points
        return [
            'badges' => array_values($awarded),
            'points' => $points,
        ];
    }

    protected function awardOnce(int $userId, string $slug, array $meta, array &$awarded): int
    {
        $badge = DB::table('badges')->where('slug', $slug)->first();
        if (!$badge) return 0;

        $exists = DB::table('user_badges')
            ->where('user_id', $userId)
            ->where('badge_id', $badge->id)
            ->exists();

        if ($exists) return 0;

        return DB::transaction(function () use ($userId, $badge, $meta, &$awarded) {
            DB::table('user_badges')->insert([
                'user_id'    => $userId,
                'badge_id'   => $badge->id,
                'meta'       => json_encode($meta),
                'awarded_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $points = (int)$badge->points_reward;
            $pk = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';
            DB::table('users')->where($pk, $userId)
                ->update(['points_total' => DB::raw('COALESCE(points_total,0) + '.$points)]);

            // ✅ Add full badge info, not just slug
            $awarded[] = [
                'slug' => $badge->slug,
                'name' => $badge->name,
                'icon' => $badge->icon ?? '🏅',
                'points_reward' => $badge->points_reward ?? 0
            ];

            Log::info("🏅 Badge '{$badge->slug}' awarded to user {$userId}");
            return $points;
        });
    }

    private function compare($a, $b, $op): bool
    {
        return match ($op) {
            '<' => $a < $b,
            '>' => $a > $b,
            '=', '==' => $a == $b,
            default => false,
        };
    }
}
