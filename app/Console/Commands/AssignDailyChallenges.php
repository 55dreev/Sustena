<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Challenge;
use App\Models\UserDailyChallenge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AssignDailyChallenges extends Command
{
    protected $signature = 'app:assign-daily-challenges';
    protected $description = 'Assign up to 4 active challenges to each user for today';

    public function handle(): int
    {
        $tz = config('app.timezone', 'Asia/Manila');
        $today = Carbon::today($tz)->toDateString();

        $this->info("Assigning daily challenges for {$today} ...");

        $activeChallenges = Challenge::where('is_active', true)->pluck('id')->all();
        if (empty($activeChallenges)) {
            $this->warn('No active challenges found. Nothing to assign.');
            return self::SUCCESS;
        }

        // Use chunkById with the correct key column: 'user_id'
        User::query()
            ->select('user_id')                               // select the right column
            ->whereNotNull('user_id')                         // optional
            ->chunkById(500, function ($users) use ($today, $activeChallenges) {
                foreach ($users as $user) {
                    $this->assignForUser((int)$user->user_id, $today, $activeChallenges);
                }
            }, 'user_id');                                    // <-- key column here

        $this->info('Done.');
        return self::SUCCESS;
    }

    protected function assignForUser(int $userId, string $dateFor, array $activeChallengeIds): void
    {
        $existing = UserDailyChallenge::where('user_id', $userId)
            ->whereDate('date_for', $dateFor)
            ->pluck('challenge_id')
            ->all();

        $alreadyCount = count($existing);
        if ($alreadyCount >= 4) return;

        $needed = 4 - $alreadyCount;
        $pool = array_values(array_diff($activeChallengeIds, $existing));
        if (empty($pool)) return;

        shuffle($pool);
        $selection = array_slice($pool, 0, $needed);

        $now = now();
        $rows = array_map(fn($chId) => [
            'user_id'      => $userId,
            'challenge_id' => $chId,
            'date_for'     => $dateFor,
            'status'       => 'not-started',
            'created_at'   => $now,
            'updated_at'   => $now,
        ], $selection);

        try {
            DB::table('user_daily_challenges')->insert($rows);
        } catch (\Throwable $e) {
            // Ignore race conditions
        }
    }
}
