<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\StreakService;
use App\Services\XpService;
use App\Models\RobotSkin;
use Carbon\Carbon;

class StreakController extends Controller
{
    /**
     * Display the streak page with user's streak data
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;

        // Get current streak
        [$streakDays, $lastActivity] = StreakService::current($userId);

        // Get all active daily tasks
        $tasks = DB::table('daily_tasks')
            ->where('is_active', true)
            ->get();

        // Get today's completed tasks for the user
        $today = Carbon::now()->toDateString();
        $completedTaskIds = DB::table('user_task_completions')
            ->where('user_id', $userId)
            ->where('completed_date', $today)
            ->pluck('task_id')
            ->toArray();

        // Mark tasks as completed
        $tasks = $tasks->map(function ($task) use ($completedTaskIds) {
            $task->is_completed = in_array($task->id, $completedTaskIds);
            return $task;
        });

        // Calculate streak badges
        $badges = [
            ['days' => 10, 'earned' => $streakDays >= 10],
            ['days' => 20, 'earned' => $streakDays >= 20],
            ['days' => 30, 'earned' => $streakDays >= 30],
        ];

        // Get user's equipped robot skin
        $equippedSkin = $user->equippedRobotSkin();
        if (!$equippedSkin) {
            // Get default skin
            $equippedSkin = RobotSkin::where('is_default', true)->first();
        }

        return view('streakpage', [
            'streak_days' => $streakDays,
            'tasks' => $tasks,
            'badges' => $badges,
            'last_activity' => $lastActivity,
            'equipped_skin' => $equippedSkin,
        ]);
    }

    /**
     * Toggle task completion
     */
    public function toggleTask(Request $request)
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;
        $taskId = $request->input('task_id');
        $today = Carbon::now()->toDateString();

        // Check if task exists
        $task = DB::table('daily_tasks')->where('id', $taskId)->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        // Check if already completed
        $existing = DB::table('user_task_completions')
            ->where('user_id', $userId)
            ->where('task_id', $taskId)
            ->where('completed_date', $today)
            ->first();

        if ($existing) {
            // Uncomplete the task
            DB::table('user_task_completions')
                ->where('id', $existing->id)
                ->delete();

            // Deduct XP
            XpService::subtract($userId, $task->xp_reward, 'task_uncompleted');

            return response()->json([
                'success' => true,
                'completed' => false,
                'message' => 'Task uncompleted',
            ]);
        } else {
            // Complete the task
            DB::table('user_task_completions')->insert([
                'user_id' => $userId,
                'task_id' => $taskId,
                'completed_date' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Award XP
            XpService::award($userId, $task->xp_reward, 'task_completed');

            // Check if all tasks for today are completed to increment streak
            $totalTasks = DB::table('daily_tasks')->where('is_active', true)->count();
            $completedToday = DB::table('user_task_completions')
                ->where('user_id', $userId)
                ->where('completed_date', $today)
                ->count();

            $allCompleted = ($completedToday >= $totalTasks);

            // Get updated streak
            [$streakDays, $lastActivity] = StreakService::current($userId);

            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Task completed! +' . $task->xp_reward . ' XP',
                'xp_earned' => $task->xp_reward,
                'all_completed' => $allCompleted,
                'streak_days' => $streakDays,
            ]);
        }
    }

    /**
     * Get streak data for API
     */
    public function getStreakData()
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;

        [$streakDays, $lastActivity] = StreakService::current($userId);

        // Get today's task completion count
        $today = Carbon::now()->toDateString();
        $totalTasks = DB::table('daily_tasks')->where('is_active', true)->count();
        $completedToday = DB::table('user_task_completions')
            ->where('user_id', $userId)
            ->where('completed_date', $today)
            ->count();

        return response()->json([
            'streak_days' => $streakDays,
            'last_activity' => $lastActivity ? $lastActivity->toDateTimeString() : null,
            'tasks_completed_today' => $completedToday,
            'total_tasks' => $totalTasks,
        ]);
    }

    /**
     * Get all robot skins for the shop
     */
    public function getShopSkins()
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;

        // Get current streak
        [$streakDays, $lastActivity] = StreakService::current($userId);

        // Get all skins
        $allSkins = RobotSkin::all();

        // Get user's purchased skins
        $purchasedSkinIds = DB::table('user_robot_skins')
            ->where('user_id', $userId)
            ->pluck('robot_skin_id')
            ->toArray();

        // Get user's equipped skin
        $equippedSkinId = DB::table('user_robot_skins')
            ->where('user_id', $userId)
            ->where('is_equipped', true)
            ->value('robot_skin_id');

        // Get user's Points total
        $userPoints = $user->points_total ?? 0;

        // Map skins with purchase/unlock status
        $skins = $allSkins->map(function ($skin) use ($streakDays, $purchasedSkinIds, $equippedSkinId, $userPoints) {
            return [
                'id' => $skin->id,
                'name' => $skin->name,
                'description' => $skin->description,
                'price' => $skin->price,
                'required_streak' => $skin->required_streak,
                'head_color' => $skin->head_color,
                'body_color' => $skin->body_color,
                'accent_color' => $skin->accent_color,
                'special_effect' => $skin->special_effect,
                'is_default' => $skin->is_default,
                'is_unlocked' => $streakDays >= $skin->required_streak,
                'is_purchased' => in_array($skin->id, $purchasedSkinIds) || $skin->is_default,
                'is_equipped' => $skin->id == $equippedSkinId,
                'can_afford' => $userPoints >= $skin->price,
            ];
        });

        return response()->json([
            'success' => true,
            'skins' => $skins,
            'user_points' => $userPoints,
            'current_streak' => $streakDays,
        ]);
    }

    /**
     * Purchase a robot skin
     */
    public function purchaseSkin(Request $request)
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;
        $skinId = $request->input('skin_id');

        // Get the skin
        $skin = RobotSkin::find($skinId);
        if (!$skin) {
            return response()->json(['error' => 'Skin not found'], 404);
        }

        // Check if already purchased
        $alreadyPurchased = DB::table('user_robot_skins')
            ->where('user_id', $userId)
            ->where('robot_skin_id', $skinId)
            ->exists();

        if ($alreadyPurchased || $skin->is_default) {
            return response()->json(['error' => 'Skin already owned'], 400);
        }

        // Get current streak
        [$streakDays, $lastActivity] = StreakService::current($userId);

        // Check if streak requirement is met
        if ($streakDays < $skin->required_streak) {
            return response()->json([
                'error' => 'Streak requirement not met',
                'required_streak' => $skin->required_streak,
                'current_streak' => $streakDays,
            ], 403);
        }

        // Check if user has enough Points
        $userPoints = $user->points_total ?? 0;
        if ($userPoints < $skin->price) {
            return response()->json([
                'error' => 'Not enough Points',
                'price' => $skin->price,
                'user_points' => $userPoints,
            ], 403);
        }

        // Deduct Points
        DB::table('users')
            ->where('user_id', $userId)
            ->decrement('points_total', $skin->price);

        // Add to user's purchased skins
        DB::table('user_robot_skins')->insert([
            'user_id' => $userId,
            'robot_skin_id' => $skinId,
            'is_equipped' => false,
            'purchased_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skin purchased successfully!',
            'remaining_points' => $userPoints - $skin->price,
        ]);
    }

    /**
     * Equip a robot skin
     */
    public function equipSkin(Request $request)
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;
        $skinId = $request->input('skin_id');

        // Get the skin
        $skin = RobotSkin::find($skinId);
        if (!$skin) {
            return response()->json(['error' => 'Skin not found'], 404);
        }

        // Check if user owns this skin
        $ownsSkin = DB::table('user_robot_skins')
            ->where('user_id', $userId)
            ->where('robot_skin_id', $skinId)
            ->exists();

        if (!$ownsSkin && !$skin->is_default) {
            return response()->json(['error' => 'Skin not owned'], 403);
        }

        // If default skin and not in user's skins, add it
        if ($skin->is_default && !$ownsSkin) {
            DB::table('user_robot_skins')->insert([
                'user_id' => $userId,
                'robot_skin_id' => $skinId,
                'is_equipped' => true,
                'purchased_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Unequip all current skins
            DB::table('user_robot_skins')
                ->where('user_id', $userId)
                ->update(['is_equipped' => false]);

            // Equip the selected skin
            DB::table('user_robot_skins')
                ->where('user_id', $userId)
                ->where('robot_skin_id', $skinId)
                ->update(['is_equipped' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skin equipped successfully!',
            'skin' => $skin,
        ]);
    }
}
