<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Connected to database";
    } catch (\Exception $e) {
        return "❌ Failed to connect: " . $e->getMessage();
    }
});

