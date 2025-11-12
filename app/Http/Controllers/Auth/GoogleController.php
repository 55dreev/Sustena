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

        Auth::login($user, true); // log them in

        return redirect()->route('landing-page'); // or whatever home route you use
    }
}
