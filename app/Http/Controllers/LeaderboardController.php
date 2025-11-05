<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->input('limit', 50);

        // Overall points leaderboard (exclude admins)
        $leaders = DB::table('users')
            ->where('is_admin', 0) // ⬅️ hide admins
            ->select(
                'user_id',
                DB::raw("
                    TRIM(
                        COALESCE(users.username, users.email)
                    ) as display_name
                "),
                DB::raw('COALESCE(points_total, 0) as points_total'),
                DB::raw('COALESCE(xp_total, 0) as xp_total'),
                DB::raw('COALESCE(level, 1) as level')
            )
            ->orderByDesc('points_total')
            ->orderBy('display_name')
            ->limit($limit)
            ->get();

        // Example recent board (kept commented). Add the same filter if you use it:
        /*
        $recent = DB::table('point_events')
            ->join('users', 'users.user_id', '=', 'point_events.user_id')
            ->where('users.is_admin', 0) // ⬅️ hide admins
            ->where('point_events.created_at', '>=', now()->subDays(7))
            ->select(
                'users.user_id',
                DB::raw("TRIM(COALESCE(users.username, users.email)) as display_name"),
                DB::raw('SUM(point_events.points) as points_7d')
            )
            ->groupBy('users.user_id', 'display_name')
            ->orderByDesc('points_7d')
            ->limit($limit)
            ->get();
        */

        return view('leader', compact('leaders'));
    }
}
