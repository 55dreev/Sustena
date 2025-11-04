<?php

use App\Http\Middleware\GuestRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FootprintController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChallengePageController;
use App\Http\Controllers\ChallengeApiController;
use App\Http\Controllers\Admin\ChallengeAdminController;
use App\Http\Controllers\ProofController;

// ✅ Moderation + Admin Settings
Route::get('/moderation', function () {
    return view('moderation');
})->name('moderation');

Route::get('/adminsettings', function () {
    return view('adminsettings');
})->name('adminsettings');



Route::get('/adminlogin', function () {
    return view('adminlogin');
})->name('adminlogin');

Route::post('/adminlogin', function (Request $request) {
    // Simple static admin login (no middleware)
    $username = $request->input('username');
    $password = $request->input('password');

    if ($username === 'admin' && $password === 'password') {
        // Directly load dashboard without middleware
        return redirect('/dashboard')->with('success', 'Welcome Admin!');
    } else {
        return back()->withErrors(['Invalid admin credentials.']);
    }
})->name('admin.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// ✅ Proof file routes
Route::middleware('auth')->group(function () {
    Route::get('/proofs/{assignment}', [ChallengeApiController::class, 'showProof'])
        ->name('proofs.show');
    Route::get('/proofs/{filename}', [ProofController::class, 'show'])
        ->name('proofs.show.file');
});

// ✅ Challenge Routes
Route::middleware(['auth'])->group(function () {
    Route::view('/challenge', 'challenge.index')->name('challenge.index');

    Route::get('/api/challenges/today', [ChallengeApiController::class, 'today']);
    Route::post('/api/challenges/{assignment}/submit-proof', [ChallengeApiController::class, 'submitProof'])->whereNumber('assignment');
    Route::post('/api/challenges/{assignment}/mark-completed', [ChallengeApiController::class, 'markCompleted'])->whereNumber('assignment');

    Route::prefix('admin')->middleware('can:manage-challenges')->group(function () {
        Route::resource('challenges', ChallengeAdminController::class)->except(['show']);
    });
});

// ✅ Forum Routes
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{post}', [ForumController::class, 'show'])->name('forum.show');

Route::middleware('auth')->group(function () {
    Route::post('/forum', [ForumController::class, 'storePost'])->name('forum.post.store');
    Route::post('/forum/{post}/comment', [ForumController::class, 'storeComment'])->name('forum.comment.store');
    Route::post('/forum/{post}/like', [ForumController::class, 'toggleLike'])->name('forum.like');
    Route::delete('/forum/{post}', [ForumController::class, 'destroyPost'])->name('forum.post.destroy');
});

Route::get('/forum/active-users', [ForumController::class, 'activeUsers'])->name('forum.activeUsers');

// ✅ Admin Routes
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users/search', [AdminController::class, 'searchUser'])->name('admin.searchUser');
Route::post('/admin/users/update', [AdminController::class, 'updateUser'])->name('admin.updateUser');
Route::post('/admin/badges/add', [AdminController::class, 'addBadge'])->name('admin.addBadge');
Route::post('/admin/badges/delete', [AdminController::class, 'deleteBadge'])->name('admin.deleteBadge');
Route::post('/admin/challenges', [AdminController::class, 'addChallenge'])->name('admin.addChallenge');
Route::post('/admin/challenges/delete', [AdminController::class, 'deleteChallenge'])->name('admin.deleteChallenge');
Route::get('/admin/challenges/manage', [AdminController::class, 'challengeManagement'])->name('admin.challenges.manage');
Route::post('/admin/challengemoderation/{id}/approve', [AdminController::class, 'approveChallenge'])->name('admin.challengemoderation.approve');
Route::post('/admin/challengemoderation/{id}/reject', [AdminController::class, 'rejectChallenge'])->name('admin.challengemoderation.reject');

// ✅ Badges
Route::get('/badges', [BadgesController::class, 'index'])->name('badges');
Route::get('/debug/badges/{attempt}', function ($attempt) {
    $u = auth()->user();
    $svc = app(\App\Services\BadgeService::class);
    $out = $svc->evaluateAttempt($u->user_id ?? $u->id, $attempt, true);
    return response()->json($out);
})->middleware('auth');

// ✅ Landing Page
Route::get('/landing-page', [HomeController::class, 'landing'])
    ->middleware('auth')
    ->name('landing-page');

// ✅ XP Route
Route::get('/me/xp', function () {
    $u = Auth::user();
    abort_unless($u, 401);
    return ['xp' => (int)($u->xp_total ?? 0), 'level' => (int)($u->level ?? 1)];
})->middleware('auth');

// ✅ Analytics API
Route::middleware('auth')->get('/analytics/summary', [AnalyticsController::class, 'summary']);

// ✅ Footprint saving
Route::middleware('auth')->group(function () {
    Route::post('/save-footprint-category-totals', [FootprintController::class, 'saveCategoryTotals']);
    Route::post('/save-footprint-score', [FootprintController::class, 'saveOverall']);
});

// ✅ Profile update
Route::post('/update-profile', function (Request $request) {
    session([
        'username' => $request->input('username'),
        'diet' => $request->input('diet'),
        'transport' => $request->input('transport'),
    ]);

    return redirect('/profile')->with('success', 'Profile updated successfully!');
})->name('update-profile');

// ✅ Footprint Score (session only)
Route::post('/save-footprint-score', function (Request $request) {
    session(['footprint_score' => $request->input('score')]);
    return redirect('/profile')->with('success', 'Footprint score updated!');
});

// ✅ Learning pages
Route::get('/waterconservation', fn() => view('waterconservation'));
Route::get('/recycling', fn() => view('recycling'));
Route::get('/climatechange', fn() => view('climatechange'));
Route::get('/energy-saving', fn() => view('energysaving'));

// ✅ Password reset
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ✅ Middleware debug routes
Route::get('/middleware-test', function () {
    $middleware = new GuestRedirect;
    return '✅ Middleware class is visible!';
});
Route::get('/debug-middleware', function () {
    return app(GuestRedirect::class) instanceof GuestRedirect
        ? '✅ Laravel resolves the middleware binding.'
        : '❌ Laravel cannot resolve the class.';
});

// ✅ Public routes
Route::middleware(\App\Http\Middleware\RedirectIfAuthenticatedCustom::class)->group(function () {
    Route::get('/', fn() => view('welcome'))->name('welcome');
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// ✅ Logout
Route::post('/logout', function () {
    session()->forget('username');
    session()->forget('previous_route');
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('welcome');
})->name('logout');

// ✅ Protected pages (logged-in only)
Route::middleware([\App\Http\Middleware\CheckAuth::class, \App\Http\Middleware\NavigationTracker::class])->group(function () {
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/landing-page', fn() => view('landing-page'))->name('landing-page');
    Route::get('/footprint-calculator', fn() => view('footprintcalc'))->name('footprint-calculator');
    Route::get('/learning-modules', fn() => view('learningmod'))->name('learning-modules');
    Route::get('/profile', fn() => view('profilepage'))->name('profile');
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

// ✅ Fallback
Route::fallback(function () {
    if (!session()->has('username') || session('username') === null) {
        return redirect()->route('welcome')->with('error', 'Please log in to access this page.');
    }
    return abort(404);
});
