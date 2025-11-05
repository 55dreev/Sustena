<?php

use App\Http\Middleware\GuestRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FootprintController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChallengeApiController;
use App\Http\Controllers\Admin\ChallengeAdminController;
use App\Http\Controllers\ProfileController;
use Carbon\Carbon;

Route::get('/debug/streak', function () {
    $uid = auth()->id();
    if (!$uid) {
        return response('Login first', 401);
    }

    $appTz = config('app.timezone', 'Asia/Manila');

    // Latest activity timestamp from DB (assumed stored in UTC)
    $lastUtc = DB::table('footprint_scores')
        ->where('user_id', $uid)
        ->max('created_at');

    $lastAt = $lastUtc ? Carbon::parse($lastUtc)->tz($appTz) : null;

    // Compute streak: build day windows in app TZ, query in DB TZ (UTC)
    $streak = 0;
    if ($lastAt) {
        $anchor = $lastAt->copy()->startOfDay();

        for ($i = 0; $i < 365; $i++) {            // <-- $i (not i)
            $startLocal = $anchor->copy()->subDays($i)->startOfDay();
            $endLocal   = $anchor->copy()->subDays($i)->endOfDay();

            $startUtc = $startLocal->copy()->tz('UTC');
            $endUtc   = $endLocal->copy()->tz('UTC');

            $had = DB::table('footprint_scores')
                ->where('user_id', $uid)
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->exists();

            if ($had) { $streak++; } else { break; }
        }
    }

    return response()->json([
        'user_id'                     => $uid,
        'now_app_tz'                  => now($appTz)->toDateTimeString(),
        'last_activity_at_app_tz'     => $lastAt?->toDateTimeString(),
        'streak_days'                 => $streak,
    ]);
})->middleware('auth');



/*
|--------------------------------------------------------------------------
| Proof streaming
|--------------------------------------------------------------------------
| - Users can view their own proof via assignment id
| - Admins can view any user's proof (secured by gate)
*/
Route::middleware('auth')->group(function () {
    Route::get('/proofs/{assignment}', [ChallengeApiController::class, 'showProof'])
        ->whereNumber('assignment')
        ->name('proofs.show');
});

Route::prefix('admin')->middleware(['auth','can:manage-challenges'])->group(function () {
    Route::get('/proofs/{assignment}', [ChallengeApiController::class, 'showProofAdmin'])
        ->whereNumber('assignment')
        ->name('admin.proofs.show');
});

/*
|--------------------------------------------------------------------------
| Challenges (user-facing)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // web.php
Route::view('/challenge', 'challenge.index')->name('challenge'); // not 'challenge.index'


    Route::get('/api/challenges/today', [ChallengeApiController::class, 'today']);
    Route::post('/api/challenges/{assignment}/submit-proof', [ChallengeApiController::class, 'submitProof'])
        ->whereNumber('assignment');
    Route::post('/api/challenges/{assignment}/mark-completed', [ChallengeApiController::class, 'markCompleted'])
        ->whereNumber('assignment');
});

/*
|--------------------------------------------------------------------------
| Admin (secured)
|--------------------------------------------------------------------------
| NOTE: Your AuthController@login should redirect admins to route('admin.dashboard')
| after login when user->is_admin is true.
*/

Route::middleware(['auth','can:manage-challenges'])->group(function () {
    Route::redirect('/adminsettings', '/admin/settings');
    Route::redirect('/moderation', '/admin/moderation');
});

Route::prefix('admin')->middleware(['auth','can:manage-challenges'])->group(function () {
    // admin dashboard (already present)
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // NEW: admin-only pages
    Route::get('/moderation', fn() => view('moderation'))->name('admin.moderation');
    Route::get('/settings', fn() => view('adminsettings'))->name('admin.settings');

    // ...rest of your admin routes...
    Route::resource('challenges', ChallengeAdminController::class)->except(['show']);
    Route::get('/users/search', [AdminController::class, 'searchUser'])->name('admin.searchUser');
    Route::post('/users/update', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::post('/badges/add', [AdminController::class, 'addBadge'])->name('admin.addBadge');
    Route::post('/badges/delete', [AdminController::class, 'deleteBadge'])->name('admin.deleteBadge');
    Route::post('/challenges', [AdminController::class, 'addChallenge'])->name('admin.addChallenge');
    Route::post('/challenges/delete', [AdminController::class, 'deleteChallenge'])->name('admin.deleteChallenge');
    Route::get('/challenges/manage', [AdminController::class, 'challengeManagement'])->name('admin.challenges.manage');
    Route::post('/challengemoderation/{id}/approve', [AdminController::class, 'approveChallenge'])->name('admin.challengemoderation.approve');
    Route::post('/challengemoderation/{id}/reject', [AdminController::class, 'rejectChallenge'])->name('admin.challengemoderation.reject');

    // Proof preview for admins (already present)
    Route::get('/proofs/{assignment}', [ChallengeApiController::class, 'showProofAdmin'])
        ->whereNumber('assignment')
        ->name('admin.proofs.show');
});


/*
|--------------------------------------------------------------------------
| Forum
|--------------------------------------------------------------------------
*/
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{post}', [ForumController::class, 'show'])->name('forum.show');

Route::middleware('auth')->group(function () {
    Route::post('/forum', [ForumController::class, 'storePost'])->name('forum.post.store');
    Route::post('/forum/{post}/comment', [ForumController::class, 'storeComment'])->name('forum.comment.store');
    Route::post('/forum/{post}/like', [ForumController::class, 'toggleLike'])->name('forum.like');
    Route::delete('/forum/{post}', [ForumController::class, 'destroyPost'])->name('forum.post.destroy');
});

/*
|--------------------------------------------------------------------------
| Moderation
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');

    // Data feeds
    Route::get('/moderation/api/comments', [ModerationController::class, 'comments'])->name('moderation.comments');
    Route::get('/moderation/api/stats',    [ModerationController::class, 'stats'])->name('moderation.stats');
    Route::get('/moderation/api/live',     [ModerationController::class, 'live'])->name('moderation.live');

    // Actions
    Route::post('/moderation/api/approve', [ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/api/delete',  [ModerationController::class, 'delete'])->name('moderation.delete');
    Route::post('/moderation/api/bulk',    [ModerationController::class, 'bulk'])->name('moderation.bulk');

    Route::prefix('moderation/api')->group(function () {
        // POSTS
        Route::get('/posts',              [ModerationController::class, 'posts'])->name('moderation.posts');
        Route::post('/post/approve',      [ModerationController::class, 'postApprove'])->name('moderation.postApprove');
        Route::post('/post/delete',       [ModerationController::class, 'postDelete'])->name('moderation.postDelete');
        Route::post('/post/bulk',         [ModerationController::class, 'postBulk'])->name('moderation.postBulk');
    });
});


Route::get('/forum/active-users', [ForumController::class, 'activeUsers'])->name('forum.activeUsers');

/*
|--------------------------------------------------------------------------
| Badges
|--------------------------------------------------------------------------
*/
Route::get('/badges', [BadgesController::class, 'index'])->name('badges');
Route::get('/debug/badges/{attempt}', function ($attempt) {
    $u = auth()->user();
    $svc = app(\App\Services\BadgeService::class);
    $out = $svc->evaluateAttempt($u->user_id ?? $u->id, $attempt, true);
    return response()->json($out);
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Landing page (controller computes XP/streaks/badges)
|--------------------------------------------------------------------------
*/
Route::get('/landing-page', [HomeController::class, 'landing'])
    ->middleware('auth')
    ->name('landing-page');

/*
|--------------------------------------------------------------------------
| Small API endpoints
|--------------------------------------------------------------------------
*/
Route::get('/me/xp', function () {
    $u = Auth::user();
    abort_unless($u, 401);
    return ['xp' => (int)($u->xp_total ?? 0), 'level' => (int)($u->level ?? 1)];
})->middleware('auth');

Route::middleware('auth')->get('/analytics/summary', [AnalyticsController::class, 'summary']);

/*
|--------------------------------------------------------------------------
| Footprint persistence
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/save-footprint-category-totals', [FootprintController::class, 'saveCategoryTotals']);
    Route::post('/save-footprint-score', [FootprintController::class, 'saveOverall']);
});

/*
|--------------------------------------------------------------------------
| Profile update
|--------------------------------------------------------------------------
*/


// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/autofill', [ProfileController::class, 'autofill'])->name('profile.autofill');
    Route::get('/profile/export.csv', [ProfileController::class, 'export'])->name('profile.export');
});

// routes/web.php
Route::get('/me/summary', function () {
    $u = Auth::user(); abort_unless($u, 401);
    $uid = $u->user_id ?? $u->id;

    // rank
    $points = (int) ($u->points_total ?? 0);
    $community = (int) DB::table('users')->count();
    $rankNum = $community ? (DB::table('users')->where('points_total', '>', $points)->count() + 1) : null;
    $rankText = $rankNum ? "#{$rankNum}" : '—';

    // day streak
    $streak = 0;
    $last = DB::table('footprint_scores')->where('user_id', $uid)->max('created_at');
    if ($last) {
        $anchor = \Carbon\Carbon::parse($last)->startOfDay();
        for ($i=0; $i<365; $i++) {
            $s = (clone $anchor)->subDays($i)->startOfDay();
            $e = (clone $anchor)->subDays($i)->endOfDay();
            $had = DB::table('footprint_scores')->where('user_id',$uid)->whereBetween('created_at',[$s,$e])->exists();
            if ($had) { $streak++; } else { break; }
        }
    }

    // energy saved this month (kWh)
    $start = now()->startOfMonth(); $end = now()->endOfMonth();
    $catIsEnergy = fn ($q) => $q->whereRaw('LOWER(ct.category)=?', ['energy']);

    $baseline = DB::table('footprint_category_totals as ct')
        ->join('footprint_scores as fs','fs.attempt_id','=','ct.attempt_id')
        ->where('fs.user_id',$uid)->where($catIsEnergy)->where('fs.created_at','<',$start)
        ->orderBy('fs.created_at','desc')->value('ct.total_score');

    $first = DB::table('footprint_category_totals as ct')
        ->join('footprint_scores as fs','fs.attempt_id','=','ct.attempt_id')
        ->where('fs.user_id',$uid)->where($catIsEnergy)->whereBetween('fs.created_at',[$start,$end])
        ->orderBy('fs.created_at','asc')->value('ct.total_score');

    $lastInMo = DB::table('footprint_category_totals as ct')
        ->join('footprint_scores as fs','fs.attempt_id','=','ct.attempt_id')
        ->where('fs.user_id',$uid)->where($catIsEnergy)->whereBetween('fs.created_at',[$start,$end])
        ->orderBy('fs.created_at','desc')->value('ct.total_score');

    $kgSaved = 0.0;
    if (!is_null($lastInMo)) {
        $kgSaved = !is_null($baseline) ? max(0,$baseline - $lastInMo)
                                       : (!is_null($first) ? max(0,$first - $lastInMo) : 0);
    }
    $energySavedKwh = round($kgSaved / 0.70, 1); // adjust factor if needed

    return [
        'rank_text'        => $rankText,
        'streak_days'      => (int) $streak,
        'energy_saved_kwh' => (float) $energySavedKwh,
    ];
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| Learning pages
|--------------------------------------------------------------------------
*/
Route::get('/waterconservation', fn() => view('waterconservation'));
Route::get('/recycling', fn() => view('recycling'));
Route::get('/climatechange', fn() => view('climatechange'));
Route::get('/energy-saving', fn() => view('energysaving'));

/*
|--------------------------------------------------------------------------
| Auth (public)
|--------------------------------------------------------------------------
*/
Route::middleware(\App\Http\Middleware\RedirectIfAuthenticatedCustom::class)->group(function () {
    Route::get('/', fn() => view('welcome'))->name('welcome');
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login'); // <- unified login
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    session()->forget('username');
    session()->forget('previous_route');
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('welcome');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected pages (extra UI pages)
|--------------------------------------------------------------------------
*/
Route::middleware([\App\Http\Middleware\CheckAuth::class, \App\Http\Middleware\NavigationTracker::class])->group(function () {
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/footprint-calculator', fn() => view('footprintcalc'))->name('footprint-calculator');
    Route::get('/learning-modules', fn() => view('learningmod'))->name('learning-modules');
    Route::get('/analytics', fn() => view('analytic'))->name('analytics');
    Route::get('/settings', fn() => view('settings'))->name('settings');
    Route::get('/streaks', fn() => view('streakpage'))->name('streaks');
    Route::get('/db-test', function () {
        try {
            DB::connection()->getPdo();
            return "✅ Connected to database";
        } catch (\Exception $e) {
            return "❌ Failed to connect: " . $e->getMessage();
        }
    })->name('db-test');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (!session()->has('username') || session('username') === null) {
        return redirect()->route('welcome')->with('error', 'Please log in to access this page.');
    }
    return abort(404);
});
