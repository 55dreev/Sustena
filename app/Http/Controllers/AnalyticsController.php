<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function summary(Request $request)
    {
        // --- Resolve user ---
        $u = Auth::guard('web')->user();
        $userId = $u?->user_id ?? $u?->id;
        if (!$userId && $request->has('user')) {
            $userId = (int) $request->query('user');
        }
        if (!$userId) {
            return response()->json([
                'has_data' => false,
                'reason'   => 'no_user',
                'cards'    => [],
                'timeseries' => [],
                'trend'    => [],
                'headline' => null,
            ]);
        }

        $limit             = max(1, min(20, (int) $request->query('limit', 5)));
        $includePracticeRq = (bool) $request->boolean('include_practice', true);

        /**
         * Helper to load attempts + category rows + overall scores.
         * We try to use kg_per_week if your schema has it; otherwise we fall back to total_score
         * (assuming your total_score is already weekly-normalized).
         */
        $load = function (bool $includePractice) use ($userId, $limit) {
            // Pull latest N attempt_ids from the category table (it has one row per category per attempt)
            $attemptsQ = DB::table('footprint_category_totals')
                ->select('attempt_id', DB::raw('MAX(created_at) as completed_at'))
                ->where('user_id', $userId);

            if (!$includePractice) {
                $attemptsQ->where('is_official', true);
            }

            $attempts = $attemptsQ
                ->groupBy('attempt_id')
                ->orderByDesc(DB::raw('MAX(created_at)'))
                ->limit($limit)
                ->get();

            $attemptIds = $attempts->pluck('attempt_id')->all();

            // Category rows for those attempts
            $catsQ = DB::table('footprint_category_totals')
                ->where('user_id', $userId)
                ->whereIn('attempt_id', $attemptIds);
            if (!$includePractice) {
                $catsQ->where('is_official', true);
            }
            $cats = $catsQ->get()->groupBy('attempt_id');

            // Overall scores for those attempts
            // Prefer kg_per_week if present; fall back to total_score
            $scoresRows = DB::table('footprint_scores')
                ->where('user_id', $userId)
                ->whereIn('attempt_id', $attemptIds)
                ->get();

            $weeklyByAttempt = [];
            foreach ($scoresRows as $r) {
                // If you created the kg_per_week column (recommended), use it first
                if (property_exists($r, 'kg_per_week') && !is_null($r->kg_per_week)) {
                    $weeklyByAttempt[$r->attempt_id] = (float) $r->kg_per_week;
                } else {
                    // Fallback: treat total_score as kg/week
                    $weeklyByAttempt[$r->attempt_id] = (float) $r->total_score;
                }
            }

            return [$attempts, $cats, $weeklyByAttempt, $includePractice];
        };

        // 1) official-only (unless the caller already asked to include practice)
        [$attempts, $cats, $weeklyByAttempt, $modeIncl] = $load($includePracticeRq);

        // 2) AUTO-FALLBACK: if we have < 2 attempts to compare, include practice
        if ($attempts->count() < 2 && !$includePracticeRq) {
            [$attempts, $cats, $weeklyByAttempt, $modeIncl] = $load(true);
        }

        if ($attempts->isEmpty()) {
            return response()->json([
                'has_data' => false,
                'cards'    => [],
                'timeseries' => [],
                'trend'    => [],
                'headline' => null,
            ]);
        }

        // Latest & previous attempt ids
        $latestAttemptId = $attempts[0]->attempt_id;
        $prevAttemptId   = $attempts->count() > 1 ? $attempts[1]->attempt_id : null;

        // ---- Category totals (latest + previous) ----
        $latestCats = collect($cats->get($latestAttemptId, collect()))
            ->mapWithKeys(fn($r) => [$r->category => (float)$r->total_score])
            ->all();

        $prevCats = $prevAttemptId
            ? collect($cats->get($prevAttemptId, collect()))
                ->mapWithKeys(fn($r) => [$r->category => (float)$r->total_score])
                ->all()
            : [];

        // ---- Overall weekly totals (prefer from footprint_scores.kg_per_week) ----
        $latestWeekly = $weeklyByAttempt[$latestAttemptId]
            ?? (float) array_sum($latestCats);

        $prevWeekly = $prevAttemptId
            ? ($weeklyByAttempt[$prevAttemptId] ?? (float) array_sum($prevCats))
            : 0.0;

        // ---- Map DB categories → UI categories ----
        $uiMap = [
            'Housing'     => ['Water Usage'],     // adjust if you later split heating/water/etc.
            'Food'        => ['Food'],
            'Travel'      => ['Transportation'],
            'Waste'       => ['Waste Management'],
            'Electricity' => ['Energy'],
        ];

        // Aggregate current categories to UI buckets
        $uiTotals = [];
        foreach ($uiMap as $ui => $src) {
            $sum = 0.0;
            foreach ($src as $c) {
                $sum += $latestCats[$c] ?? 0.0;
            }
            $uiTotals[$ui] = round($sum, 2);
        }

        // Percent shares against this attempt total
        $uiPercents = [];
        foreach ($uiTotals as $ui => $val) {
            $uiPercents[$ui] = $latestWeekly > 0 ? (int) round(($val / $latestWeekly) * 100, 0) : 0;
        }

        // Per-category deltas vs previous attempt
        $uiDeltas = [];
        if ($prevAttemptId && $prevWeekly > 0) {
            foreach ($uiMap as $ui => $src) {
                $prevSum = 0.0;
                foreach ($src as $c) {
                    $prevSum += $prevCats[$c] ?? 0.0;
                }
                $abs = round($uiTotals[$ui] - $prevSum, 2);
                $pct = $prevSum > 0 ? round(($abs / $prevSum) * 100, 1) : null;
                $uiDeltas[$ui] = ['abs' => $abs, 'pct' => $pct];
            }
        }

        // ---- Build cards (weekly-based) ----
        $cards = [];
        foreach ($uiTotals as $ui => $val) {
            $cards[] = [
                'title'        => $ui,
                'kg_per_week'  => round($val, 1),
                'percent'      => $uiPercents[$ui],
                'delta'        => $uiDeltas[$ui] ?? null,
            ];
        }

        // ---- Delta headline vs previous ----
        $deltaPct = ($prevWeekly > 0)
            ? round((($latestWeekly - $prevWeekly) / $prevWeekly) * 100, 1)
            : null;

        // ---- Target: user target or rolling avg of last 5 previous attempts ----
        $targetAbsWeekly = null;
        $targetSource    = null;

        if (!is_null($u?->target_co2) && (float)$u->target_co2 > 0) {
            $targetAbsWeekly = (float) $u->target_co2; // assume already weekly
            $targetSource    = 'user';
        } else {
            // rolling avg of last 5 previous attempts, same mode (official or official+practice)
            $prevIds = $attempts->pluck('attempt_id')->slice(1, 5)->values();
            $values  = [];
            foreach ($prevIds as $aid) {
                $wk = $weeklyByAttempt[$aid] ?? (float) collect($cats->get($aid, collect()))->sum('total_score');
                if ($wk > 0) $values[] = $wk;
            }
            if (count($values) > 0) {
                $targetAbsWeekly = array_sum($values) / count($values);
                $targetSource    = 'rolling_avg';
            }
        }

        // ---- Time series (oldest → newest) with weekly totals + category splits ----
        $orderedAttempts = $attempts->reverse()->values(); // now oldest → newest
        $timeseries = [];
        foreach ($orderedAttempts as $a) {
            $aid    = $a->attempt_id;
            $date   = Carbon::parse($a->completed_at)->toDateString();
            $wk     = $weeklyByAttempt[$aid]
                ?? (float) collect($cats->get($aid, collect()))->sum('total_score');

            // Per-attempt categories (raw DB categories) → keep as-is so frontend can use them
            $catMap = [];
            foreach (($cats->get($aid, collect())) as $c) {
                $catMap[$c->category] = (float) $c->total_score;
            }

            $timeseries[] = [
                'date'          => $date,
                'total_weekly'  => (float) round($wk, 1),
                'categories'    => $catMap,
            ];
        }

        // ---- Backward compat: simple trend (no category splits)
        $trend = [];
        foreach ($orderedAttempts as $a) {
            $aid   = $a->attempt_id;
            $date  = Carbon::parse($a->completed_at)->toDateString();
            $wk    = $weeklyByAttempt[$aid]
                ?? (float) collect($cats->get($aid, collect()))->sum('total_score');

            $trend[] = [
                'attempt_id'  => $aid,
                'date'        => $date,
                'total'       => (float) round($wk, 1),
            ];
        }

        return response()->json([
            'has_data' => true,
            'headline' => [
                // canonical weekly number for the current attempt
                'kg_per_week'        => (float) round($latestWeekly, 1),
                'delta_pct'          => $deltaPct,             // % vs previous attempt
                'target_abs_weekly'  => $targetAbsWeekly,      // optional absolute weekly target
                'mode'               => $modeIncl ? 'official+practice' : 'official',
            ],
            'cards'      => $cards,        // [{title, kg_per_week, percent, delta?}]
            'timeseries' => $timeseries,   // [{date, total_weekly, categories:{...}}] oldest→newest
            'trend'      => $trend,        // backward compat (no categories)
            'attempt'    => [
                'id'           => $latestAttemptId,
                'completed_at' => (string) $attempts[0]->completed_at,
            ],
        ]);
    }
}
