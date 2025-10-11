<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->input('limit', 50);

        // Overall points leaderboard
        $leaders = DB::table('users')
            ->select(
                'user_id',
                DB::raw("
                    TRIM(
                        COALESCE(
                            users.username,
                            users.email
                        )
                    ) as display_name
                "),
                'points_total',
                'xp_total',
                'level'
            )
            ->orderByDesc('points_total')
            ->orderBy('display_name')
            ->limit($limit)
            ->get();

        // Example (optional): last 7 days leaderboard using user_id
        // $recent = DB::table('point_events')
        //     ->join('users', 'users.user_id', '=', 'point_events.user_id')
        //     ->where('point_events.created_at', '>=', now()->subDays(7))
        //     ->select(
        //         'users.user_id',
        //         DB::raw("TRIM(COALESCE(users.username, users.email)) as display_name"),
        //         DB::raw('SUM(point_events.points) as points_7d')
        //     )
        //     ->groupBy('users.user_id', 'display_name')
        //     ->orderByDesc('points_7d')
        //     ->limit($limit)
        //     ->get();

        return view('leader', compact('leaders'));
    }
}
