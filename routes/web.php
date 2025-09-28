<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/footprint-calculator', function () {
    return view('footprintcalc'); // no .blade.php here!
});

Route::get('/landing-page', function () {
    return view('landing'); // no .blade.php here!
});

Route::get('/learn', function () {
    return view('learningmod'); // no .blade.php here!
});

Route::get('/challenges', function () {
    return view('challenge'); // no .blade.php here!
});

Route::get('/microforum', function () {
    return view('forum'); // no .blade.php here!
});

Route::get('/profile', function () {
    return view('profilepage'); // no .blade.php here!
});

Route::get('/streak', function () {
    return view('streakpage'); // no .blade.php here!
});

Route::get('/anal', function () {
    return view('analytic'); // no .blade.php here!
});

Route::get('/leaderboard', function () {
    return view('leader'); // no .blade.php here!
});

Route::get('/badge', function () {
    return view('badges'); // no .blade.php here!
});

Route::get('/setting', function () {
    return view('settings'); // no .blade.php here!
});


