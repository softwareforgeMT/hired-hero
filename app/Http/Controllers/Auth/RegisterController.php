<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     * We won't redirect directly because user must verify email first.
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form.
     * (If you want to customize, otherwise uses default)
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Validate the registration form data.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],

            // reCAPTCHA validation
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET'),
                    'response' => $value,
                ]);

                if (!$response->json('success')) {
                    $fail('Captcha verification failed, please try again.');
                }
            }],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        // Handle avatar upload
        $avatarName = null;
        if (request()->hasFile('avatar')) {
            $avatar = request()->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('/images/'), $avatarName);
        }

        // Create user with is_email_verified = 0 by default
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $avatarName,
            'is_email_verified' => 0,
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return $user;
    }

    /**
     * After user registers, logout and redirect to login page with message
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        return redirect()->route('user.login')->with('info', 'Please verify your email address to complete registration.');
    }
}
