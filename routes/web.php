<?php

use App\Http\Middleware\GuestRedirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use App\Http\Controllers\FootprintController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
// routes/web.php
use App\Http\Controllers\LeaderboardController;
// routes/web.php
use App\Http\Controllers\BadgesController;
// routes/web.php
// routes/web.php
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;

Route::get('/forum', [ForumController::class,'index'])->name('forum.index');
Route::get('/forum/{post}', [ForumController::class,'show'])->name('forum.show');

Route::middleware('auth')->group(function () {
    Route::post('/forum', [ForumController::class,'storePost'])->name('forum.post.store');
    Route::post('/forum/{post}/comment', [ForumController::class,'storeComment'])->name('forum.comment.store');
    Route::post('/forum/{post}/like', [ForumController::class,'toggleLike'])->name('forum.like');
    Route::delete('/forum/{post}', [ForumController::class,'destroyPost'])->name('forum.post.destroy');
});

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users/search', [AdminController::class, 'searchUser'])->name('admin.searchUser');
Route::post('/admin/users/update', [AdminController::class, 'updateUser'])->name('admin.updateUser');
Route::post('/admin/badges/add', [AdminController::class, 'addBadge'])->name('admin.addBadge');
Route::delete('/admin/badges/{id}', [AdminController::class, 'deleteBadge']);
Route::post('/admin/challenges/add', [AdminController::class, 'addChallenge'])->name('admin.addChallenge');



// routes/web.php
Route::get('/forum/active-users', [ForumController::class, 'activeUsers'])
     ->name('forum.activeUsers');



Route::get('/badges', [BadgesController::class, 'index'])->name('badges');

// routes/web.php
Route::get('/debug/badges/{attempt}', function ($attempt) {
    $u = auth()->user();
    $svc = app(\App\Services\BadgeService::class);
    $out = $svc->evaluateAttempt($u->user_id ?? $u->id, $attempt, true);
    return response()->json($out);
})->middleware('auth');



Route::get('/landing-page', [HomeController::class, 'landing'])
    ->middleware('auth')   // remove if not using auth
    ->name('landing-page');


Route::get('/me/xp', function () {
    $u = Auth::user();
    abort_unless($u, 401);
    return ['xp' => (int)($u->xp_total ?? 0), 'level' => (int)($u->level ?? 1)];
})->middleware('auth');

Route::middleware('auth')->get('/analytics/summary', [AnalyticsController::class, 'summary']);


Route::middleware('auth')->group(function () {
    Route::post('/save-footprint-category-totals', [FootprintController::class, 'saveCategoryTotals']);
    Route::post('/save-footprint-score',           [FootprintController::class, 'saveOverall']); // if you also save overall
});


Route::post('/update-profile', function (Request $request) {
    session([
        'username' => $request->input('username'),
        'diet' => $request->input('diet'),
        'transport' => $request->input('transport'),
    ]);

    return redirect('/profile')->with('success', 'Profile updated successfully!');
})->name('update-profile');

Route::post('/save-footprint-score', function (Request $request) {
    // Save score to session
    session(['footprint_score' => $request->input('score')]);

    // Redirect back to profile
    return redirect('/profile')->with('success', 'Footprint score updated!');
});


Route::get('/waterconservation', function () {
    return view('waterconservation'); 
});

Route::get('/recycling', function () {
    return view('recycling'); 
});

Route::get('/climatechange', function () {
    return view('climatechange');
});

Route::get('/energy-saving', function () {
    return view('energysaving');
});



Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');



Route::get('/middleware-test', function () {
    $middleware = new GuestRedirect;
    return '✅ Middleware class is visible!';
});
Route::get('/debug-middleware', function () {
    return app(GuestRedirect::class) instanceof GuestRedirect
        ? '✅ Laravel resolves the middleware binding.'
        : '❌ Laravel cannot resolve the class.';
});

// Public routes - accessible without authentication
Route::middleware(\App\Http\Middleware\RedirectIfAuthenticatedCustom::class)->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');


});


 // Logout route (only accessible when logged in)
 Route::post('/logout', function () {
    session()->forget('username');
    session()->forget('previous_route');
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('welcome');
})->name('logout');


// Protected routes - require authentication and proper navigation
Route::middleware([\App\Http\Middleware\CheckAuth::class, \App\Http\Middleware\NavigationTracker::class])->group(function () {

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    // Protected pages - require login
    Route::get('/landing-page', function () {
        return view('landing-page');
    })->name('landing-page');
    
    Route::get('/challenge', function () {
        return view('challenge');
    })->name('challenge');

    Route::get('/footprint-calculator', function () {
        return view('footprintcalc');
    })->name('footprint-calculator');

    Route::get('/learning-modules', function () {
        return view('learningmod');
    })->name('learning-modules');

    Route::get('/profile', function () {
        return view('profilepage');
    })->name('profile');

    // ✅ Added New Pages
    Route::get('/analytics', function () {
        return view('analytic');
    })->name('analytics');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/streaks', function () {
        return view('streakpage');
    })->name('streaks');

    // DB connection test (only for authenticated users)
    Route::get('/db-test', function () {
        try {
            DB::connection()->getPdo();
            return "✅ Connected to database";
        } catch (\Exception $e) {
            return "❌ Failed to connect: " . $e->getMessage();
        }
    })->name('db-test');

});

// Catch-all route for unauthorized access attempts
Route::fallback(function () {
    if (!session()->has('username') || session('username') === null) {
        return redirect()->route('welcome')->with('error', 'Please log in to access this page.');
    }
    return abort(404);
});