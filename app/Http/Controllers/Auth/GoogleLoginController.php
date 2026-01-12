<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request, GoogleAuthService $googleAuthService): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        $socialUser = $driver->stateless()->user();

        try {
            $user = $googleAuthService->authenticateOrFail($socialUser);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('filament.admin.auth.login')->withErrors(['email' => $e->getMessage()]);
        }

        Auth::login($user, true);

        // Redirect to Filament dashboard path (uses config fallback)
        return redirect()->intended(config('filament.path', '/admin'));
    }
}
