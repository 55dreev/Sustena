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
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
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
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string', // can be username or email
        'password' => 'required|string',
        'accept_eula' => 'required|accepted',
    ], [
        'accept_eula.required' => 'You must accept the EULA to log in.',
        'accept_eula.accepted' => 'You must accept the EULA to log in.',
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

        // Mark EULA as accepted
        if (!$user->eula_accepted) {
            $user->eula_accepted = true;
            $user->eula_accepted_at = now();
            $user->save();
        }

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
