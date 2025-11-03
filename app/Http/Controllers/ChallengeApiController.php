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
    /**
     * Get today's challenges for the authenticated user.
     */
    public function today(Request $request)
    {
        $user   = $request->user();
        $userId = $user->getAuthIdentifier();

        $tz        = $user->timezone ?? config('app.timezone', 'Asia/Manila');
        $now       = Carbon::now($tz);
        $todayDate = $now->toDateString();
        $refreshAt = Carbon::tomorrow($tz)->startOfDay();
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
                    'date_for'     => $todayDate,
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
            'challenges'         => $assignments->map(fn($a) => [
                'assignment_id' => $a->id,
                'title'         => $a->challenge->title,
                'subtitle'      => $a->challenge->subtitle,
                'description'   => $a->challenge->description,
                'difficulty'    => (int) $a->challenge->difficulty,
                'points'        => '+' . (int) $a->challenge->points_xp . ' XP',
                'icon'          => $a->challenge->icon,
                'status'        => $a->status,
                'proof_url'     => $a->proof_path ? route('proofs.show', $a->id) : null,
            ]),
        ]);
    }

    /**
     * Submit a proof for a challenge.
     */
    public function submitProof(Request $request, UserDailyChallenge $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        $request->validate([
            'proof' => ['required','image','max:4096'],
        ]);

        $path = $request->file('proof')->store('proofs', 'local');

        $assignment->update([
            'proof_path'   => $path,
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Show proof file for authorized user.
     */
    public function showProof(Request $request, UserDailyChallenge $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        $path = $assignment->proof_path;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $mime = Storage::disk('local')->mimeType($path) ?? 'application/octet-stream';
        $contents = Storage::disk('local')->get($path);
        return response($contents, 200)->header('Content-Type', $mime);
    }

    /**
     * Mark a challenge assignment as completed and award XP.
     */
    public function markCompleted(Request $request, UserDailyChallenge $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        if ($assignment->status === 'completed') {
            return response()->json(['ok' => true, 'status' => 'completed']);
        }

        [$payload, $alreadyCompleted] = DB::transaction(function () use ($request, $assignment) {
            $fresh = UserDailyChallenge::query()->whereKey($assignment->id)->lockForUpdate()->first();

            if ($fresh->status === 'completed') {
                return [['ok' => true, 'status' => 'completed'], true];
            }

            $fresh->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            $xpAward = (int) optional($fresh->challenge)->points_xp ?? 0;

            $award = ['awarded_xp' => 0, 'xp_total' => null, 'level' => null];
            if ($xpAward > 0) {
                $user = $request->user();
                $userId = $user->getAuthIdentifier();
                $source = 'challenge:' . $fresh->id;

                // Award XP properly to existing columns
                $award = XpService::awardFixedXp($userId, $source, $xpAward, Carbon::now());

                // Make sure your XpService updates xp_total, xp_today, xp_this_week
            }

            return [[
                'ok'        => true,
                'status'    => 'completed',
                'added'     => (int) ($award['awarded_xp'] ?? 0),
                'xp_total'  => (int) ($award['xp_total'] ?? 0),
                'xp_today'  => (int) ($award['xp_today'] ?? 0),
                'xp_week'   => (int) ($award['xp_this_week'] ?? 0),
                'level'     => (int) ($award['level'] ?? 0),
            ], false];
        });

        return response()->json($payload);
    }

    /**
     * Approve a user-submitted challenge proof.
     */
    public function approveChallenge(UserDailyChallenge $assignment)
    {
        if ($assignment->status === 'approved') {
            return response()->json(['ok' => true, 'status' => 'approved']);
        }

        $assignment->update([
            'status'       => 'approved',
            'completed_at' => now(),
        ]);

        // Award XP when approved (if not already completed)
        if ($assignment->status !== 'completed') {
            $userId = $assignment->user_id;
            $source = 'challenge:' . $assignment->id;
            $xpAward = (int) optional($assignment->challenge)->points_xp ?? 0;

            if ($xpAward > 0) {
                XpService::awardFixedXp($userId, $source, $xpAward, Carbon::now());
            }
        }

        return response()->json(['ok' => true, 'status' => 'approved']);
    }

    /**
     * Reject a user-submitted challenge proof.
     */
    public function rejectChallenge(UserDailyChallenge $assignment)
    {
        $assignment->update([
            'status' => 'rejected',
        ]);

        return response()->json(['ok' => true, 'status' => 'rejected']);
    }

    /**
     * Ensure the current user owns this assignment.
     */
    protected function authorizeAssignment(Request $request, UserDailyChallenge $assignment): void
    {
        $uid = (int) $request->user()->getAuthIdentifier();
        abort_unless((int) $assignment->user_id === $uid, 403, 'Forbidden.');
    }
}
