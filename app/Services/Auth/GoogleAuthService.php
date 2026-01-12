<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class GoogleAuthService
{
    /**
     * Find an existing user by Google email and update google_id when necessary.
     *
     * @param SocialiteUser $socialiteUser
     * @return User
     * @throws AuthenticationException
     */
    public function authenticateOrFail(SocialiteUser $socialiteUser): User
    {
        $email = $socialiteUser->getEmail();

        if (empty($email)) {
            throw new AuthenticationException('Google account did not provide an email.');
        }

        // Use an efficient lookup
        $user = User::firstWhere('email', $email);

        if (! $user) {
            throw new AuthenticationException('No application account is associated with this Google account.');
        }

        // Keep db writes minimal and idempotent
        if (is_null($user->google_id) || $user->google_id !== $socialiteUser->getId()) {
            $user->forceFill([
                'google_id' => $socialiteUser->getId(),
            ])->save();
        }

        return $user;
    }
}
