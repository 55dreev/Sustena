<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Show landing-page.blade.php when visiting the root URL (/)
Route::get('/', function () {
    return view('landing-page');
})->name('home');

// Welcome page (for login/signup)
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Register & Login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout and redirect to welcome page
Route::post('/logout', function () {
    session()->forget('username');
    return redirect()->route('landing-page');
})->name('logout');

// Other pages
Route::get('/challenge', function () {
    return view('challenge');
});

Route::get('/footprint-calculator', function () {
    return view('footprintcalc');
});

Route::get('/forum', function () {
    return view('forum');
});

Route::get('/learning-modules', function () {
    return view('learningmod');
});

Route::get('/profile', function () {
    return view('profilepage');
});

// DB connection test
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Connected to database";
    } catch (\Exception $e) {
        return "❌ Failed to connect: " . $e->getMessage();
    }
});
