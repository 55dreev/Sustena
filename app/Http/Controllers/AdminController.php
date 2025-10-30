<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::orderBy('user_id', 'desc')->take(5)->get();
        $badges = Badge::all();

        return view('admin', [
            'users' => $users,
            'badges' => $badges,
        ]);
    }

    public function searchUser(Request $request)
    {
        $query = $request->get('q');
        $user = User::where('username', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->first();

        return response()->json($user);
    }

    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update($request->only('name', 'email'));
        return response()->json(['success' => true]);
    }

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

        // ✅ Correct rule handling
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

    public function deleteBadge($id)
{
    $badge = \App\Models\Badge::find($id);
    if (!$badge) {
        return response()->json(['success' => false, 'message' => 'Badge not found.'], 404);
    }

    $badge->delete();

    return response()->json([
        'success' => true,
        'message' => '🗑️ Badge deleted successfully!'
    ]);
}


    public function downloadData()
    {
        return response()->json(['message' => 'Download triggered']);
    }
}
