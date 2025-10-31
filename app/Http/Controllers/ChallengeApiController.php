<?php
// app/Http/Controllers/ChallengeApiController.php
namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\UserDailyChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Services\XpService;
use Illuminate\Support\Facades\DB;

class ChallengeApiController extends Controller
{
  // app/Http/Controllers/ChallengeApiController.php



public function today(Request $request)
{
    $user   = $request->user();
    $userId = $user->getAuthIdentifier();

    $tz        = $user->timezone ?? config('app.timezone', 'Asia/Manila');
    $now       = Carbon::now($tz);
    $todayDate = $now->toDateString();                     // YYYY-MM-DD (PHT)
    $refreshAt = Carbon::tomorrow($tz)->startOfDay();      // ⬅️ next midnight 00:00
    $secondsLeft = max(0, $now->diffInSeconds($refreshAt, false));

    $assignments = UserDailyChallenge::with('challenge')
        ->where('user_id', $userId)
        ->where('date_for', $todayDate)
        ->get();

    if ($assignments->count() < 4) {
        $existingIds = $assignments->pluck('challenge_id')->all();

        $toAssign = Challenge::query()
            ->where('is_active', true)
            ->whereNotIn('id', $existingIds)
            ->inRandomOrder()
            ->take(4 - $assignments->count())
            ->get();

        foreach ($toAssign as $ch) {
            UserDailyChallenge::create([
                'user_id'      => $userId,
                'challenge_id' => $ch->id,
                'date_for'     => $todayDate,  // store DATE only
                'status'       => 'not-started',
            ]);
        }

        $assignments = UserDailyChallenge::with('challenge')
            ->where('user_id', $userId)
            ->where('date_for', $todayDate)
            ->get();
    }

    return response()->json([
        'refresh_in_seconds' => $secondsLeft,
        'refresh_at'         => $refreshAt->toIso8601String(),
        'server_now'         => $now->toIso8601String(),
        'challenges'         => $assignments->map(function ($a) {
            return [
                'assignment_id' => $a->id,
                'title'         => $a->challenge->title,
                'subtitle'      => $a->challenge->subtitle,
                'description'   => $a->challenge->description,
                'difficulty'    => (int) $a->challenge->difficulty,
                'points'        => '+' . (int) $a->challenge->points_xp . ' XP',
                'icon'          => $a->challenge->icon,
                'status'        => $a->status,
                'proof_url' => $a->proof_path ? route('proofs.show', $a->id) : null,

            ];
        }),
    ]);
}




public function submitProof(Request $request, UserDailyChallenge $assignment)
{
    $this->authorizeAssignment($request, $assignment);

    $request->validate([
        'proof' => ['required','image','max:4096'], // 4MB
    ]);

    // Store privately (storage/app/proofs/...)
    $path = $request->file('proof')->store('proofs', 'local'); // ⬅️ no "public" disk

    $assignment->update([
        'proof_path'   => $path,     // e.g. "proofs/abc123.jpg"
        'status'       => 'pending',
        'submitted_at' => now(),
    ]);

    return response()->json(['ok' => true]);
}

public function showProof(Request $request, UserDailyChallenge $assignment)
{
    // Only the owner or an authorized admin should see it
    $this->authorizeAssignment($request, $assignment); // your existing check

    $path = $assignment->proof_path; // e.g. "proofs/abc.jpg"
    if (!$path || !Storage::disk('local')->exists($path)) {
        abort(404);
    }

    // Stream the file with correct mime type
    $mime = Storage::disk('local')->mimeType($path) ?? 'application/octet-stream';
    $contents = Storage::disk('local')->get($path);
    return response($contents, 200)->header('Content-Type', $mime);
}


public function markCompleted(Request $request, UserDailyChallenge $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        // If already completed, just return current status
        if ($assignment->status === 'completed') {
            return response()->json(['ok' => true, 'status' => 'completed']);
        }

        [$payload, $alreadyCompleted] = DB::transaction(function () use ($request, $assignment) {
            // Re-read with a lock to avoid races
            $fresh = UserDailyChallenge::query()->whereKey($assignment->id)->lockForUpdate()->first();

            if ($fresh->status === 'completed') {
                // Someone else already completed it during the race
                return [['ok' => true, 'status' => 'completed'], true];
            }

            // Mark the assignment completed
            $fresh->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            // Award fixed XP from the related challenge (idempotent by sourceKey)
            $xpAward = (int) optional($fresh->challenge)->points_xp ?? 0;

            $award = ['awarded_xp' => 0, 'xp_total' => null, 'level' => null];
            if ($xpAward > 0) {
                $userId   = $request->user()->getAuthIdentifier();
                $source   = 'challenge:' . $fresh->id;        // unique per assignment
                $award    = XpService::awardFixedXp($userId, $source, $xpAward, Carbon::now());
            }

            return [[
                'ok'      => true,
                'status'  => 'completed',
                'added'   => (int) ($award['awarded_xp'] ?? 0), // XP just added
                'xp'      => (int) ($award['xp_total']   ?? 0), // new total
                'level'   => (int) ($award['level']      ?? 0), // new level
            ], false];
        });

        return response()->json($payload);
    }



  // app/Http/Controllers/ChallengeApiController.php

protected function authorizeAssignment(Request $request, UserDailyChallenge $assignment): void
{
    $uid = (int) $request->user()->getAuthIdentifier(); // respects custom $primaryKey
    abort_unless((int) $assignment->user_id === $uid, 403, 'Forbidden.');
}

}
