<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Send user to Google’s login/consent screen
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            // If something fails, send back to login
            return redirect()->route('welcome')
                ->with('error', 'Google login failed, please try again.');
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            // Create a new local user
            $user = User::create([
                'username'  => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
                'email'     => $googleUser->getEmail(),
                'password'  => bcrypt(Str::random(32)), // random password
                'google_id' => $googleUser->getId(),
            ]);
        } else {
            // Update google_id if needed
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        // Check if user has accepted EULA
        if (!$user->eula_accepted) {
            // Store user ID in session temporarily to complete login after EULA acceptance
            session(['pending_google_user_id' => $user->user_id ?? $user->id]);
            return redirect()->route('auth.google.eula');
        }

        // Complete the login
        $this->completeLogin($user);

        return redirect()->route('landing-page')->with('success', 'Google login successful!');
    }

    public function showEulaForm()
    {
        // Check if there's a pending Google user
        if (!session()->has('pending_google_user_id')) {
            return redirect()->route('welcome');
        }

        return view('auth.accept-eula');
    }

    public function acceptEula()
    {
        request()->validate([
            'accept_eula' => 'required|accepted',
        ], [
            'accept_eula.required' => 'You must accept the EULA to continue.',
            'accept_eula.accepted' => 'You must accept the EULA to continue.',
        ]);

        $userId = session('pending_google_user_id');
        if (!$userId) {
            return redirect()->route('welcome')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::where('user_id', $userId)->orWhere('id', $userId)->first();
        if (!$user) {
            return redirect()->route('welcome')->with('error', 'User not found.');
        }

        // Mark EULA as accepted
        $user->eula_accepted = true;
        $user->eula_accepted_at = now();
        $user->save();

        // Complete the login
        $this->completeLogin($user);

        // Clear pending session
        session()->forget('pending_google_user_id');

        return redirect()->route('landing-page')->with('success', 'Welcome to Sustena!');
    }

    private function completeLogin($user)
    {
        // Log in using Laravel's Auth system
        Auth::login($user, true);

        // Set custom session variables for CheckAuth middleware
        session(['username' => $user->username]);
        session(['previous_route' => 'landing-page']);

        // Regenerate session for security
        request()->session()->regenerate();
    }
}
