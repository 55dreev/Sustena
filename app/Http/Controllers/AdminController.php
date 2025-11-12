<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\Challenge;
use App\Models\User;
use App\Models\UserDailyChallenge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard overview
     */
    public function dashboard()
{
    $users = User::orderBy('user_id', 'desc')->take(5)->get();
    $badges = Badge::all();
    $challenges = Challenge::orderBy('id', 'desc')->take(50)->get();
    $pendingChallenges = UserDailyChallenge::with(['user', 'challenge'])
        ->where('status', 'pending')
        ->orderBy('submitted_at', 'desc')
        ->get();

    return view('admin', compact('users', 'badges', 'challenges', 'pendingChallenges'));
}


    /**
     * Search user by name or email
     */
    public function searchUser(Request $request)
    {
        $query = $request->get('q');
        $user = User::where('username', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->first();

        return response()->json($user);
    }

    /**
     * Update user info
     */
    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update($request->only('name', 'email'));

        return response()->json(['success' => true]);
    }

    /**
     * Add a new badge
     */
    public function addBadge(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'rule' => 'nullable',
            'points' => 'nullable|numeric',
        ]);

        $badge = new Badge();
        $badge->name = $data['name'];
        $badge->slug = Str::slug($data['name']);
        $badge->icon = '🏅';
        $badge->category = $data['category'] ?? 'general';
        $badge->points_reward = $data['points'] ?? 0;

        if (isset($data['rule'])) {
            if (is_string($data['rule'])) {
                $decoded = json_decode($data['rule'], true);
                $badge->rule = $decoded ?: [];
            } elseif (is_array($data['rule'])) {
                $badge->rule = $data['rule'];
            } else {
                $badge->rule = [];
            }
        } else {
            $badge->rule = [];
        }

        $badge->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Badge added successfully!',
            'badge' => $badge,
        ]);
    }

    /**
     * Delete a badge
     */
    public function deleteBadge(Request $request)
{
    $id = $request->input('id'); 
    $badge = Badge::find($id);

    if (!$badge) {
        return response()->json(['success' => false, 'message' => 'Badge not found.']);
    }

    $badge->delete();

    return response()->json(['success' => true, 'message' => 'Badge deleted.']);
}

    /**
     * Add a new challenge
     */
    public function addChallenge(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|integer|min:1|max:5',
            'points_xp' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $challenge = new Challenge();
        $challenge->title = $data['title'];
        $challenge->subtitle = $data['subtitle'] ?? '';
        $challenge->description = $data['description'] ?? '';
        $challenge->difficulty = $data['difficulty'] ?? 1;
        $challenge->points_xp = $data['points_xp'] ?? 50;
        $challenge->icon = $data['icon'] ?? '🎯';
        $challenge->is_active = $data['is_active'] ?? true;
        $challenge->save();

        return response()->json([
            'success' => true,
            'message' => '🏆 Challenge added successfully!',
            'challenge' => $challenge,
        ]);
    }

    /**
     * Delete a challenge
     */
    public function deleteChallenge(Request $request)
{
    $id = $request->input('id'); // get from POST body
    $challenge = Challenge::find($id);

    if (!$challenge) {
        return response()->json(['success' => false, 'message' => 'Challenge not found.']);
    }

    $challenge->delete();

    return response()->json(['success' => true, 'message' => 'Challenge deleted.']);
}


    /**
     * Download all admin data (stub)
     */
    public function downloadData()
    {
        return response()->json(['message' => 'Download triggered']);
    }

    /* -----------------------------------------------------------------
       🧾 FEEDBACK & MODERATION SECTION
    ------------------------------------------------------------------*/

    /**
     * Show pending user submissions for moderation
     */
    public function moderation()
{
    $pending = UserDailyChallenge::with(['challenge', 'user'])
        ->where('status', 'pending')
        ->orderBy('submitted_at', 'desc')
        ->get();

    return view('moderation', [
        'pending' => $pending,
    ]);
}


   public function approveChallenge($id)
{
    $submission = UserDailyChallenge::find($id);
    if (!$submission) {
        return response()->json(['success' => false, 'message' => 'Submission not found.']);
    }

    $user = $submission->user;
    $challenge = $submission->challenge;

    if (!$user || !$challenge) {
        return response()->json(['success' => false, 'message' => 'User or challenge not found.']);
    }

    // Award XP
    $xpEarned = $challenge->points_xp ?? 0;
    $user->xp_total += $xpEarned;
    $user->xp_today += $xpEarned;
    $user->xp_this_week += $xpEarned;
    $user->save();

    // Mark submission as approved
    $submission->status = 'completed';
    $submission->completed_at = now();
    $submission->save();

    return response()->json(['success' => true, 'message' => "Challenge approved. User gained {$xpEarned} XP."]);
}


public function rejectChallenge($id)
{
    $submission = UserDailyChallenge::find($id);

    if (!$submission) {
        return response()->json(['success' => false, 'message' => 'Submission not found.'], 404);
    }

    $submission->status = 'not-started';  // ✅ Use valid ENUM value
    $submission->completed_at = null;     // Optional: reset completed_at if needed
    $submission->save();

    return response()->json(['success' => true, 'message' => '❌ Challenge rejected successfully.']);
}

public function researchSummary(Request $request)
{
    // (Optional) protect this: only admins who can moderate
    $u = $request->user();
    if (!$u || (method_exists($u, 'can') && !$u->can('manage-challenges'))) {
        return response()->json([
            'has_data' => false,
            'reason'   => 'forbidden',
        ], 403);
    }

    $includePractice = (bool) $request->boolean('include_practice', true);
    $basis           = $request->query('basis', 'weekly');
    $scope           = 'admin_research';
    $anon            = (bool) $request->boolean('anon', true);

    // date_from / date_to coming from query string (we send date_to from the picker)
    $fromDate = $request->filled('date_from')
        ? Carbon::parse($request->query('date_from'))->startOfDay()
        : null;

    $toDate = $request->filled('date_to')
        ? Carbon::parse($request->query('date_to'))->endOfDay()
        : null;

    // --------- load ALL category rows (no user_id filter) ----------
    $catsQ = DB::table('footprint_category_totals');

    if (!$includePractice) {
        $catsQ->where('is_official', true);
    }

    if ($fromDate && $toDate) {
        $catsQ->whereBetween('created_at', [$fromDate, $toDate]);
    } elseif ($fromDate) {
        $catsQ->where('created_at', '>=', $fromDate);
    } elseif ($toDate) {
        $catsQ->where('created_at', '<=', $toDate);
    }

    $cats = $catsQ->get();

    if ($cats->isEmpty()) {
        return response()->json([
            'has_data'    => false,
            'reason'      => 'no_rows',
            'cards'       => [],
            'timeseries'  => [],
            'trend'       => [],
            'headline'    => null,
            'export_meta' => [
                'scope'      => $scope,
                'basis'      => $basis,
                'date_from'  => $fromDate?->toDateString(),
                'date_to'    => $toDate?->toDateString(),
                'anonymized' => $anon,
            ],
        ]);
    }

    // prefer kg_per_week per row; fall back to total_score
    $cats = $cats->map(function ($r) {
        if (property_exists($r, 'kg_per_week') && !is_null($r->kg_per_week)) {
            $r->val_weekly = (float) $r->kg_per_week;
        } else {
            $r->val_weekly = (float) $r->total_score;
        }
        return $r;
    });

    // --------- aggregate by category across all users ----------
    $byCategory = [];
    $grandTotal = 0.0;

    foreach ($cats->groupBy('category') as $category => $rows) {
        $sum = $rows->sum('val_weekly');
        $byCategory[$category] = $sum;
        $grandTotal += $sum;
    }

    $cards = [];
    foreach ($byCategory as $category => $sum) {
        $percent = $grandTotal > 0 ? round(($sum / $grandTotal) * 100, 1) : 0.0;
        $cards[] = [
            'title'       => $category,
            'kg_per_week' => round($sum, 2),
            'percent'     => $percent,
            'delta'       => null,   // no previous window for global research
        ];
    }

    // --------- build daily time series (all users combined) ----------
    $tsMap = [];  // key = date string
    foreach ($cats as $r) {
        $date = Carbon::parse($r->created_at)->toDateString();

        if (!isset($tsMap[$date])) {
            $tsMap[$date] = [
                'date'         => $date,
                'total_weekly' => 0.0,
                'categories'   => [],
            ];
        }

        $tsMap[$date]['total_weekly'] += $r->val_weekly;
        $tsMap[$date]['categories'][$r->category] =
            ($tsMap[$date]['categories'][$r->category] ?? 0.0) + $r->val_weekly;
    }

    ksort($tsMap);                    // oldest → newest
    $timeseries = array_values($tsMap);

    // simple trend: just total per day
    $trend = [];
    foreach ($timeseries as $row) {
        $trend[] = [
            'date'  => $row['date'],
            'total' => round($row['total_weekly'], 2),
        ];
    }

    // global headline
    $headline = [
        'kg_per_week'        => round($grandTotal, 2),
        'delta_pct'          => null,
        'target_abs_weekly'  => null,
        'mode'               => $includePractice ? 'official+practice' : 'official',
    ];

    $exportMeta = [
        'scope'      => $scope,
        'basis'      => $basis,
        'date_from'  => $fromDate?->toDateString(),
        'date_to'    => $toDate?->toDateString(),
        'anonymized' => $anon,
    ];

    return response()->json([
        'has_data'    => true,
        'headline'    => $headline,
        'cards'       => $cards,
        'timeseries'  => $timeseries,
        'trend'       => $trend,
        'export_meta' => $exportMeta,
    ]);
}


}
