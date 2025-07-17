<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            DB::table('users')->insert([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_registration' => now(),
            ]);
            return redirect('/')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', '❌ Registration failed: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // ✅ #1 FIX: Store username in session
            session(['username' => $user->username]);

            return redirect('/landing-page');
        }

        return back()->withErrors([
            'loginError' => 'Invalid credentials.',
        ])->withInput();
    }
}
