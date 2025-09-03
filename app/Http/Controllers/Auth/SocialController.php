<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            $authUser = $this->findOrCreateUser($user, 'google');
            
            Auth::login($authUser, true);
            
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }
    }

    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function handleGithubCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
            
            $authUser = $this->findOrCreateUser($user, 'github');
            
            Auth::login($authUser, true);
            
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'GitHub authentication failed.');
        }
    }

    /**
     * Redirect the user to the Facebook authentication page.
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            
            $authUser = $this->findOrCreateUser($user, 'facebook');
            
            Auth::login($authUser, true);
            
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook authentication failed.');
        }
    }

    /**
     * Find or create a user based on social provider data.
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // First, check if the user already exists with this provider and provider ID
        $authUser = User::where('provider', $provider)
                        ->where('provider_id', $socialUser->getId())
                        ->first();

        if ($authUser) {
            return $authUser;
        }

        // If not found, check if a user exists with the same email
        $authUser = User::where('email', $socialUser->getEmail())->first();

        if ($authUser) {
            // Update the user with provider information
            $authUser->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
            
            return $authUser;
        }

        // If no user exists, create a new one
        return User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'email' => $socialUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(),
        ]);
    }
}