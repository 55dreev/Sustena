<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BadgeService
{
    public function evaluateAttempt(
        int $userId,
        string $attemptId,
        bool $includePractice = true   // include practice attempts in “previous” lookup
    ): array {
        $awarded = [];
        $points  = 0;

        // ------ load this attempt ------
        $score = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->where('attempt_id', $attemptId)
            ->first();

        if (!$score) {
            return ['badges' => [], 'points' => 0];
        }

        // Use weekly everywhere
        $weekly = !is_null($score->kg_per_week) ? (float)$score->kg_per_week
                                                : (float)$score->total_score;

        // Category totals (use kg_per_week; fallback to total_score)
        $cats = DB::table('footprint_category_totals')
            ->where('user_id', $userId)
            ->where('attempt_id', $attemptId)
            ->get(['category','kg_per_week','total_score']);

        $nowCats = collect();
        foreach ($cats as $r) {
            $nowCats[$r->category] = !is_null($r->kg_per_week) ? (float)$r->kg_per_week
                                                               : (float)$r->total_score;
        }

        // ------ previous attempt (now includes practice by default) ------
        $prevAttempt = DB::table('footprint_category_totals')
            ->select('attempt_id', DB::raw('MAX(created_at) as done'))
            ->where('user_id', $userId)
            ->where('attempt_id', '<>', $attemptId)
            ->when(!$includePractice, fn ($q) => $q->where('is_official', 1))
            ->groupBy('attempt_id')
            ->orderByDesc(DB::raw('MAX(created_at)'))
            ->first();

        $prevWeekly = null;
        $prevCats   = collect();

        if ($prevAttempt) {
            $prevCatsRows = DB::table('footprint_category_totals')
                ->where('user_id', $userId)
                ->where('attempt_id', $prevAttempt->attempt_id)
                ->get(['category','kg_per_week','total_score']);

            foreach ($prevCatsRows as $r) {
                $prevCats[$r->category] = !is_null($r->kg_per_week) ? (float)$r->kg_per_week
                                                                    : (float)$r->total_score;
            }

            $prevScore = DB::table('footprint_scores')
                ->where('user_id', $userId)
                ->where('attempt_id', $prevAttempt->attempt_id)
                ->first();

            if ($prevScore) {
                $prevWeekly = !is_null($prevScore->kg_per_week) ? (float)$prevScore->kg_per_week
                                                                : (float)$prevScore->total_score;
            }
        }

        // ------------- RULES -------------

        // R1: Carbon Under 100 (weekly) — works for practice too
        if ($weekly > 0 && $weekly < 100) {
            $points += $this->awardOnce($userId, 'carbon-under-100', [
                'weekly'     => $weekly,
                'attempt_id' => $attemptId
            ], $awarded);
        }

        // R2: Waste Reducer (Silver): waste down ≥10% vs previous attempt
        if ($prevCats->isNotEmpty()) {
            $prevWaste = (float) ($prevCats['Waste Management'] ?? 0);
            $nowWaste  = (float) ($nowCats['Waste Management'] ?? 0);
            if ($prevWaste > 0) {
                $dropPct = (($prevWaste - $nowWaste) / $prevWaste) * 100;
                if ($dropPct >= 10) {
                    $points += $this->awardOnce($userId, 'waste-reducer-silver', [
                        'prev'       => $prevWaste,
                        'now'        => $nowWaste,
                        'drop_pct'   => round($dropPct, 1),
                        'attempt_id' => $attemptId
                    ], $awarded);
                }
            }
        }

        // R3: Level milestone 10 (any run)
        $pk = Schema::hasColumn('users','user_id') ? 'user_id' : 'id';
$user = DB::table('users')->where($pk, $userId)->first();
        $level = (int)($user->level ?? 1);
        if ($level >= 10) {
            $points += $this->awardOnce($userId, 'level-10', ['level' => $level], $awarded);
        }

        return ['badges' => $awarded, 'points' => $points];
    }

    protected function awardOnce(int $userId, string $slug, array $meta, array &$awarded): int
    {
        $badge = DB::table('badges')->where('slug', $slug)->first();
        if (!$badge) return 0;

        $has = DB::table('user_badges')
            ->where('user_id', $userId)
            ->where('badge_id', $badge->id)
            ->exists();

        if ($has) return 0;

        return DB::transaction(function () use ($userId, $badge, $meta, &$awarded) {
            DB::table('user_badges')->insert([
                'user_id'    => $userId,
                'badge_id'   => $badge->id,
                'meta'       => json_encode($meta),
                'awarded_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $points = (int) $badge->points_reward;
            $pk = Schema::hasColumn('users','user_id') ? 'user_id' : (Schema::hasColumn('users','id') ? 'id' : null);
if ($pk) {
    DB::table('users')
      ->where($pk, $userId)
      ->update(['points_total' => DB::raw('COALESCE(points_total,0) + '.$points)]);
}

            $awarded[] = $badge->slug;
            return $points;
        });
    }
}
