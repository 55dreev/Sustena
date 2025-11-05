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
     * where the user has at least one footprint_scores row.
     */
    public static function current(int $userId, string $tz = 'Asia/Manila'): array
    {
        $now   = Carbon::now($tz)->startOfDay();
        $dates = DB::table('footprint_scores')
            ->where('user_id', $userId)
            ->selectRaw('DATE(CONVERT_TZ(created_at, @@session.time_zone, ?)) as d', [$tz])
            ->groupBy('d')
            ->orderByDesc('d')
            ->pluck('d'); // array of 'YYYY-MM-DD'

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
