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

            // Set session
            session(['username' => $request->username]);
            session()->forget('previous_route');

            return redirect('/')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', '❌ Registration failed: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string', // can be username or email
        'password' => 'required|string',
    ]);

    // Try username first
    $user = User::where('username', $request->username)->first();

    // If the input looks like an email and username lookup failed, try email
    if (!$user && filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
        $user = User::where('email', $request->username)->first();
    }

    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        $request->session()->regenerate(); // protect session fixation

        // Keep your existing session values
        session(['username' => $user->username]);
        session(['previous_route' => 'landing-page']);

        // ✅ Redirect admins to the admin dashboard, others to landing page
        if (!empty($user->is_admin)) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
        }
        return redirect()->route('landing-page')->with('success', 'Login successful!');
    }

    return back()->withErrors(['loginError' => 'Invalid credentials.'])->withInput();
}

}
