<?php

namespace App;

use App\Helper;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\CentralLogics\Mailchimp;
use Illuminate\Auth\Events\Registered;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $settings = GeneralSetting::first();

        // Check if the user already exists by provider ID
        $user = User::where('oauth_provider', $provider)
                    ->where('oauth_uid', $providerUser->getId())
                    ->first();

        if (!$user) {
            // Check if email exists
            $email = $providerUser->getEmail();
            if (!$email) {
                return back()->with('error', 'The email address associated with your social account is missing. Please use another method.');
            }

            // Check if a user with the same email exists
            $userEmail = User::where('email', $email)->first();
            if ($userEmail) {
                return back()->with('error', 'The email address is already registered. Please log in instead.');
            }

            // Set verification status
            $verify = $settings->email_verification == '1' ? 0 : 1;

            // Create user
            $user = User::create([
                'name' => $providerUser->getName() ?? $providerUser->getNickname() ?? 'Unknown',
                'email' => strtolower($email),
                'password' => Hash::make(Str::random(16)),
                'oauth_uid' => $providerUser->getId(),
                'oauth_provider' => $provider,
                'is_email_verified' => $verify,
            ]);

            // Send email verification if required
            if ($verify === 0) {
                event(new Registered($user));
            }

            // Assign avatar (optional, default to user.png)
            $user->avatar = 'user.png';
            $user->save();

            // Subscribe to mailchimp
            Mailchimp::SubscribeToMailChimp($user);

            // Generate affiliate code
            if ($settings->is_affilate == 1) {
                $referred_by = Session::get('affilate', '');

                do {
                    $affiliate_code = substr(uniqid(), 0, 8);
                } while (User::where('affiliate_code', $affiliate_code)->exists());

                $user->update([
                    'affiliate_code' => $affiliate_code,
                    'referred_by' => $referred_by,
                ]);
            }

            // Activate Free Plan
            $user->activateFreePlan();
        }

        return $user;
    }
}