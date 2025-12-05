<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // ===== OVERVIEW STATISTICS =====
        $total_users = DB::table('users')->count();

        // Active users = users with footprint attempts in last 30 days
        $active_users = DB::table('footprint_scores')
            ->where('created_at', '>=', now()->subDays(30))
            ->distinct('user_id')
            ->count('user_id');

        $total_attempts = DB::table('footprint_scores')->count();
        $official_attempts = DB::table('footprint_scores')
            ->where('is_official', true)
            ->count();

        // ===== CARBON FOOTPRINT TRENDS =====
        // Average carbon footprint over time (last 12 months)
        $monthly_avg = DB::table('footprint_scores')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('AVG(kg_per_week) as avg_kg'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->whereNotNull('kg_per_week')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // ===== CATEGORY BREAKDOWN =====
        // Average emissions by category (last 30 days)
        $category_avg = DB::table('footprint_category_totals as ct')
            ->join('footprint_scores as fs', 'fs.attempt_id', '=', 'ct.attempt_id')
            ->select(
                'ct.category',
                DB::raw('AVG(ct.kg_per_week) as avg_kg'),
                DB::raw('COUNT(DISTINCT fs.user_id) as user_count')
            )
            ->where('fs.created_at', '>=', now()->subDays(30))
            ->whereNotNull('ct.kg_per_week')
            ->groupBy('ct.category')
            ->orderBy('avg_kg', 'desc')
            ->get();

        // ===== USER ENGAGEMENT =====
        // Daily active users (last 30 days)
        $daily_active = DB::table('footprint_scores')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as users')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // ===== IMPROVEMENT METRICS =====
        // Users showing improvement (current vs previous attempt)
        $improvement_stats = $this->calculateImprovementStats();

        // ===== TOP PERFORMERS =====
        $top_reducers = DB::table('users')
            ->select('username as name', 'email', 'xp_total', 'level', 'points_total')
            ->orderBy('points_total', 'desc')
            ->limit(10)
            ->get();

        // ===== STREAK STATISTICS =====
        $streak_stats = [
            'avg_streak' => DB::table('users')->avg('streak_weeks') ?? 0,
            'max_streak' => DB::table('users')->max('streak_weeks') ?? 0,
            'active_streaks' => DB::table('users')->where('streak_weeks', '>', 0)->count(),
        ];

        // ===== CHALLENGE COMPLETION =====
        $challenge_stats = [
            'total_challenges' => DB::table('challenges')->count(),
            'total_assignments' => DB::table('user_daily_challenges')->count(),
            'completed' => DB::table('user_daily_challenges')
                ->where('status', 'completed')
                ->count(),
            'pending' => DB::table('user_daily_challenges')
                ->where('status', 'pending')
                ->count(),
        ];

        $completion_rate = $challenge_stats['total_assignments'] > 0
            ? round(($challenge_stats['completed'] / $challenge_stats['total_assignments']) * 100, 1)
            : 0;

        // ===== DISTRIBUTION ANALYSIS =====
        // Carbon footprint distribution (buckets)
        $distribution = DB::table('footprint_scores')
            ->select(DB::raw('
                CASE
                    WHEN kg_per_week < 50 THEN "< 50 kg"
                    WHEN kg_per_week < 100 THEN "50-100 kg"
                    WHEN kg_per_week < 150 THEN "100-150 kg"
                    WHEN kg_per_week < 200 THEN "150-200 kg"
                    ELSE "> 200 kg"
                END as bucket,
                COUNT(*) as count
            '))
            ->whereNotNull('kg_per_week')
            ->groupBy('bucket')
            ->get();

        // ===== RECENT ACTIVITY =====
        $recent_attempts = DB::table('footprint_scores as fs')
            ->join('users as u', 'u.user_id', '=', 'fs.user_id')
            ->select(
                'u.username as name',
                'fs.kg_per_week',
                'fs.total_score',
                'fs.is_official',
                'fs.created_at'
            )
            ->orderBy('fs.created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.analytics-dashboard', compact(
            'total_users',
            'active_users',
            'total_attempts',
            'official_attempts',
            'monthly_avg',
            'category_avg',
            'daily_active',
            'improvement_stats',
            'top_reducers',
            'streak_stats',
            'challenge_stats',
            'completion_rate',
            'distribution',
            'recent_attempts'
        ));
    }

    private function calculateImprovementStats()
    {
        $users = DB::table('users')->pluck('user_id');
        $improved = 0;
        $worsened = 0;
        $unchanged = 0;

        foreach ($users as $uid) {
            $two = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->whereNotNull('kg_per_week')
                ->orderByDesc('id')
                ->limit(2)
                ->pluck('kg_per_week')
                ->values();

            if ($two->count() === 2) {
                $latest = (float) $two[0];
                $prev = (float) $two[1];

                if ($latest < $prev) $improved++;
                elseif ($latest > $prev) $worsened++;
                else $unchanged++;
            }
        }

        return [
            'improved' => $improved,
            'worsened' => $worsened,
            'unchanged' => $unchanged,
            'total_compared' => $improved + $worsened + $unchanged,
        ];
    }
}
