<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const SUPPORTED_PROVIDERS = ['google', 'facebook', 'twitter'];

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::SUPPORTED_PROVIDERS), 404);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, self::SUPPORTED_PROVIDERS), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Social login failed. Please try again.');
        }

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'              => $socialUser->getName(),
                'username'          => Str::slug($socialUser->getName()) . '_' . Str::random(4),
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'provider'          => $provider,
                'provider_id'       => $socialUser->getId(),
                'is_active'         => true,
            ]
        );

        // Update provider info if existing user
        if (! $user->wasRecentlyCreated) {
            $user->update(['provider' => $provider, 'provider_id' => $socialUser->getId()]);
        }

        // Assign default subscriber role if no roles
        if (! $user->roles->count()) {
            $user->assignRole('subscriber');
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('home', ['locale' => $user->locale ?? 'dv']));
    }
}
