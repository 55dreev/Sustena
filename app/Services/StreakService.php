<?php
// app/Services/StreakService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StreakService
{
    /**
     * Returns [days, lastActivityAtCarbon]
     * Streak = number of consecutive days (today, yesterday, …)
     * where the user has at least one footprint_scores row OR completed all daily tasks.
     */
    public static function current(int $userId, string $tz = 'Asia/Manila'): array
    {
        $now   = Carbon::now($tz)->startOfDay();

        // Get dates from footprint_scores
        $footprintDates = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->selectRaw('DATE(CONVERT_TZ(created_at, @@session.time_zone, ?)) as d', [$tz])
            ->groupBy('d')
            ->pluck('d')
            ->toArray();

        // Get dates where user completed ALL daily tasks
        $taskCompletionDates = DB::table('user_task_completions')
            ->where('user_id', $userId)
            ->select('completed_date')
            ->groupBy('completed_date')
            ->havingRaw('COUNT(DISTINCT task_id) >= (SELECT COUNT(*) FROM daily_tasks WHERE is_active = 1)')
            ->pluck('completed_date')
            ->toArray();

        // Merge and deduplicate dates
        $dates = collect(array_merge($footprintDates, $taskCompletionDates))
            ->unique()
            ->sortDesc()
            ->values();

        $days = 0;
        $expect = $now->copy();           // today
        $lastAt = null;

        foreach ($dates as $d) {
            $dC = Carbon::parse($d, $tz)->startOfDay();
            $lastAt ??= $dC->copy()->endOfDay();
            if ($dC->equalTo($expect)) {
                $days++;
                $expect->subDay();
            } elseif ($dC->equalTo($expect->copy()->addDay())) {
                // user is active yesterday but not today; allow streak to start at yesterday
                if ($days === 0) {
                    $expect = $dC->copy(); // reset to yesterday
                    $days   = 1;
                    $expect->subDay();
                } else {
                    break;
                }
            } else {
                break;
            }
        }

        return [$days, $lastAt];
    }
}
