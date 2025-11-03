<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\Challenge;
use App\Models\User;
use App\Models\UserDailyChallenge;
use Illuminate\Support\Str;

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


}
