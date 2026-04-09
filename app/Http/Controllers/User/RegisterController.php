<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\Mailchimp;
use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    public function __construct()
    {
        /**
         * Important:
         * - Registration pages should be guest-only.
         * - Google callback must NOT be blocked when user is being logged in.
         * We'll keep guest for the register/login type pages, but allow callback to run safely.
         */
        $this->middleware('guest')->except([
            'submitGoogleReferral',
            'skipGoogleReferral',
        ]);
    }

    // -------------------------------------------
    // Show registration page
    // -------------------------------------------
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    // -------------------------------------------
    // Manual Registration
    // -------------------------------------------
    public function register(Request $request)
    {
        $ipAddress = $request->ip();
        $cookies   = json_encode($_COOKIE);

        $existingTracking = UserLog::where('ip_address', $ipAddress)
            ->orWhere('cookies', $cookies)
            ->first();

        $request->validate([
            'name'           => 'required|unique:users',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed',
            'referral_code'  => 'nullable|string|exists:users,affiliate_code'
        ], [
            'name.unique' => 'The username has already been taken.',
        ]);
        
        // If duplicate detected & not confirmed, bounce back with flag
        if ($existingTracking && !$request->has('confirm_duplicate')) {
            session(['temp_password' => $request->input('password')]);

            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->with([
                    'duplicate_found_register' => true,
                    'ip_address' => $ipAddress,
                    'cookies' => $cookies,
                    'password' => $request->input('password'),
                ]);
        }

        $gs = GeneralSetting::find(1);

        $input = $request->all();
        $input['role_id']  = 1;
        $input['password'] = bcrypt($request->input('password'));
        $input['discount'] = 'allow';

        // Affiliate / referral logic
        if ($gs && $gs->is_affilate == 1) {
            $referred_by = null;

            if ($request->filled('referral_code')) {
                $referrer = User::where('affiliate_code', $request->referral_code)->first();
                if ($referrer) {
                    $referred_by = $request->referral_code;
                }
            } elseif (Session::has('affilate')) {
                $referred_by = Session::get('affilate');
            }

            do {
                $affiliate_code = substr(uniqid(), 0, 8);
            } while (User::where('affiliate_code', $affiliate_code)->exists());

            $input['affiliate_code'] = $affiliate_code;
            $input['referred_by']    = $referred_by;
        }

        // Create user
        $user = new User();
        $user->fill($input)->save();

        // Track IP & cookies
        $status = ($existingTracking && $request->has('confirm_duplicate')) ? 'flag' : 'active';

        UserLog::create([
            'user_id'    => $user->id,
            'ip_address' => $ipAddress,
            'cookies'    => $cookies,
            'status'     => $status,
        ]);

        // If flagged, flag old record too
        if ($existingTracking && $request->has('confirm_duplicate')) {
            UserLog::where('ip_address', $ipAddress)
                ->orWhere('cookies', $cookies)
                ->update(['status' => 'flag']);

            User::where('id', $user->id)->update(['discount' => 'stop']);
            User::where('id', $existingTracking->user_id)->update(['discount' => 'stop']);
        }

        // Activate plan + mailchimp
        $user->activateFreePlan();
        Mailchimp::SubscribeToMailChimp($user);

        // Email verification
        if ($gs && $gs->email_verification == 1) {
            $response = Helpers::send_verification_otp($user->email);
            if (isset($response['success'])) {
                // Store redirect parameters in session for after verification (only if they have values)
                if ($request->filled('redirect')) {
                    session([
                        'placement_redirect_route' => $request->input('redirect'),
                    ]);
                    if ($request->filled('step')) {
                        session(['placement_redirect_step' => $request->input('step')]);
                    }
                }
                return redirect()->back()->with([
                    'showVerificationModal' => true,
                    'email' => $user->email
                ]);
            }

            return redirect()->back()->withErrors([
                'error' => $response['error'] ?? 'Verification failed.'
            ]);
        } else {
            $user->is_email_verified = 1;
            $user->save();
            SendWelcomeEmail::dispatch($user);
        }

        // ✅ Log in user and go to dashboard
        Auth::guard('web')->login($user, true);

        // Check for custom redirect parameters (from placement wizard)
        if ($request->filled('redirect')) {
            $redirectRoute = $request->input('redirect');
            $redirectParams = [];
            if ($request->filled('step')) {
                $redirectParams['step'] = $request->input('step');
            }
            return redirect()->route($redirectRoute, $redirectParams)
                ->with('success', $status == 'flag'
                    ? 'Account Registered Successfully (Flagged for duplicate IP/Cookies).'
                    : 'Account Registered Successfully');
        }

        return redirect()->intended(route('user.dashboard'))
            ->with('success', $status == 'flag'
                ? 'Account Registered Successfully (Flagged for duplicate IP/Cookies).'
                : 'Account Registered Successfully');
    }

    // -------------------------------------------
    // GOOGLE AUTH
    // -------------------------------------------

    public function redirectToGoogle(Request $request)
    {
        // Store redirect parameters in session before redirecting to Google (only if they have values)
        if ($request->filled('redirect')) {
            session(['google_redirect_route' => $request->input('redirect')]);
            if ($request->filled('step')) {
                session(['google_redirect_step' => $request->input('step')]);
            }
        }
        
        // ✅ Use stateless to avoid session/state issues on mobile/safari/cloudflare
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $email    = $googleUser->getEmail();
            $googleId = $googleUser->getId();
            $name     = $googleUser->getName() ?: ($googleUser->getNickname() ?: 'User');
            $avatar   = $googleUser->getAvatar();

            // Track IP & cookies
            $ipAddress = $request->ip();
            $cookies   = json_encode($_COOKIE);

            $existingTracking = UserLog::where('ip_address', $ipAddress)
                ->orWhere('cookies', $cookies)
                ->first();

            $confirmDuplicate = session('confirm_duplicate_google', false);

            if(Session::has('new_user_banner')){
                Session::forget('new_user_banner');
            }

            // ✅ Find user by email FIRST (prevents duplicates)
            $user = User::where('email', $email)->first();

            // If user exists but google_id missing, attach it
            if ($user && empty($user->google_id)) {
                $user->google_id = $googleId;
                if (!empty($avatar)) {
                    $user->avatar = $avatar;
                }
                $user->save();
            }

            // If still no user, create new user (but respect duplicate tracking)
            if (!$user) {
                if ($existingTracking && !$confirmDuplicate) {
                    // IMPORTANT: don't force register page; user can't get into portal
                    // Instead: show message on login page
                    return redirect()->route('user.login')->withErrors([
                        'error' => 'We detected unusual activity from this device/network. Please create an account manually or contact support.'
                    ]);
                }

                // Optional referral from session
                $referrerAffiliate = null;
                if (session()->has('affilate')) {
                    $referrer = User::where('affiliate_code', session('affilate'))->first();
                    if ($referrer) {
                        $referrerAffiliate = $referrer->affiliate_code;
                    }
                }

                // Generate unique affiliate code
                do {
                    $affiliate_code = substr(uniqid(), 0, 8);
                } while (User::where('affiliate_code', $affiliate_code)->exists());

                $user = User::create([
                    'google_id'         => $googleId,
                    'name'              => $name,
                    'email'             => $email,
                    'avatar'            => $avatar,
                    'password'          => bcrypt(Str::random(24)),
                    'affiliate_code'    => $affiliate_code,
                    'referred_by'       => $referrerAffiliate,
                    'role_id'           => 1,
                    'is_email_verified' => 1,
                    'discount'          => 'allow',
                ]);

                Helpers::send_hiredhero_welcome($user);
                Helpers::welcomeEmailToAdmin($user);

                // Log tracking
                UserLog::create([
                    'user_id'    => $user->id,
                    'ip_address' => $ipAddress,
                    'cookies'    => $cookies,
                    'status'     => ($existingTracking && $confirmDuplicate) ? 'flag' : 'active',
                ]);

                if ($existingTracking && $confirmDuplicate) {
                    UserLog::where('ip_address', $ipAddress)
                        ->orWhere('cookies', $cookies)
                        ->update(['status' => 'flag']);

                    User::where('id', $user->id)->update(['discount' => 'stop']);
                    User::where('id', $existingTracking->user_id)->update(['discount' => 'stop']);
                }

                // Activate plan + subscribe
                $user->activateFreePlan();
                Mailchimp::SubscribeToMailChimp($user);
            }

            // ✅ ALWAYS LOGIN FIRST (this was your main portal-blocking bug)
            Auth::guard('web')->login($user, true);

            // Check for custom redirect parameters (from placement wizard, stored in session during redirectToGoogle)
            $redirectRoute = null;
            $redirectParams = [];
            
            // First check session (stored in redirectToGoogle method)
            if (session()->has('google_redirect_route')) {
                $redirectRoute = session('google_redirect_route');
                if (session()->has('google_redirect_step')) {
                    $redirectParams['step'] = session('google_redirect_step');
                }
                session()->forget('google_redirect_route');
                session()->forget('google_redirect_step');
            } else if (request()->has('redirect')) {
                // Fallback to query parameters
                $redirectRoute = request()->query('redirect');
                if (request()->has('step')) {
                    $redirectParams['step'] = request()->query('step');
                }
            }
            
            if ($redirectRoute) {
                return redirect()->route($redirectRoute, $redirectParams);
            }

            // Referral modal logic AFTER login:
            // If referred_by is empty, we can prompt them inside dashboard
            if (empty($user->referred_by)) {
                session(['new_google_user_id' => $user->id]);
                session()->forget('confirm_duplicate_google');

                return redirect()->route('user.dashboard')
                    ->with('showReferralModal', true)
                    ->with('user_id', $user->id);
            }

            session()->forget('confirm_duplicate_google');

            return redirect()->intended(route('user.dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('user.login')->withErrors([
                'error' => 'Google login failed. Please try again.'
            ]);
        }
    }

    // -------------------------------------------
    // Referral submission (after Google login)
    // -------------------------------------------

    public function submitGoogleReferral(Request $request)
    {
        $request->validate([
            'referral_code' => 'nullable|string',
            'user_id'       => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return redirect()->route('user.login')->withErrors([
                'error' => 'User not found. Please sign in again.'
            ]);
        }

        $referrer = null;

        if ($request->filled('referral_code')) {
            $referrer = User::where('affiliate_code', $request->referral_code)->first();
            if (!$referrer) {
                // Keep them logged in; show modal again
                session(['new_google_user_id' => $user->id]);

                return redirect()->route('user.dashboard')
                    ->with('showReferralModal', true)
                    ->with('user_id', $user->id)
                    ->withErrors(['error' => 'Referral Code is incorrect.']);
            }
        }

        $user->update([
            'referred_by' => $referrer ? $referrer->affiliate_code : null,
        ]);

        Auth::guard('web')->login($user, true);
        session()->forget('new_google_user_id');

        return redirect()->route('user.dashboard')->with('success', 'Referral saved successfully!');
    }

    public function skipGoogleReferral()
    {
        $userId = session('new_google_user_id');

        if (!$userId) {
            return redirect()->route('user.login')->withErrors([
                'error' => 'Session expired. Please sign in again.'
            ]);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('user.login')->withErrors([
                'error' => 'User not found. Please sign in again.'
            ]);
        }

        $user->update(['referred_by' => null]);

        Auth::guard('web')->login($user, true);
        session()->forget('new_google_user_id');

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully!');
    }

    public function confirmGoogleDuplicate(Request $request)
    {
        session(['confirm_duplicate_google' => true]);
        return redirect()->route('user.google-redirect');
    }
}
