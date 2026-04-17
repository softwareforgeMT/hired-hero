<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Session;
use App\Jobs\SendWelcomeEmail;
class LoginController extends Controller
{

    public function __construct()
    {
         $this->middleware('guest')->only('showLoginForm','authenticationToken','newAuthenticationToken');
    }

    public function showLoginForm()
    {
      return view('user.auth.login');
    }

    public function login(Request $request)
    {      
      if(Session::has('new_user_banner')){
          Session::forget('new_user_banner');
      }

        $this->validate($request,
        [
                  'email'   => 'required|email',
                  'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (isset($user) && password_verify($request->password, $user->password) ) {

          $gs=GeneralSetting::find(1);
          if($user->status!=1){
            return back()->with('error','Your account has been blocked.');
          }
          if($gs->email_verification==1 && $user->is_email_verified!=1){
            $response=Helpers::send_verification_otp($user->email);
            if(isset($response['success'])){
              // Store redirect parameters in session for after verification (only if they have values)
              if ($request->filled('redirect')) {
                  session([
                      'placement_redirect_route' => $request->input('redirect'),
                  ]);
                  if ($request->filled('step')) {
                      session(['placement_redirect_step' => $request->input('step')]);
                  }
              }
              return redirect()->back()->with(['showVerificationModal' => true, 'email' => $user->email]);
            }else{ return redirect()->back()->with('error',$response['error']);}
          }else{
             // Auth::guard('web')->login($user);
              if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                    // Check for custom redirect parameters (from placement wizard)
                    if ($request->filled('redirect')) {
                        $redirectRoute = $request->input('redirect');
                        $redirectParams = [];
                        if ($request->filled('step')) {
                            $redirectParams['step'] = $request->input('step');
                        }
                        return redirect()->route($redirectRoute, $redirectParams);
                    }
                    // if successful, then redirect to their intended location
                    return redirect()->intended(route('user.dashboard'));
               }
          }
        }
        Session::flash('message', 'Credentials Doesn\'t Match !');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back();

    }

    
    public function logout($value='')
    {   
        // Check if admin is impersonating - prevent logout
        if (session('impersonating')) {
            return redirect()->back()->with('error', 'You cannot logout while being impersonated by an admin. Please ask the admin to revert the impersonation first.');
        }

        Auth::guard('web')->logout();
        return redirect()->route('front.index');
    }


    public function authenticationToken(Request $request)
    {   
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return view('errors.404');
        }
        $verify = EmailVerifications::where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if ($verify) {
            $verify->delete(); 
            $user->is_email_verified=1;
            $user->update();
            // SendWelcomeEmail::dispatch($user);
            Auth::guard('web')->login($user);
            Helpers::send_hiredhero_welcome($user);
            Helpers::welcomeEmailToAdmin($user);
            if(Session::has('new_user_banner')){
                Session::forget('new_user_banner');
            }
            
            // Check for stored redirect parameters (from placement wizard)
            $redirectRoute = session('placement_redirect_route');
            $redirectStep = session('placement_redirect_step');
            session()->forget(['placement_redirect_route', 'placement_redirect_step']);
            
            // Only use redirect if it's valid, otherwise go to dashboard
            if (!empty($redirectRoute) && $redirectRoute !== 'user.dashboard') {
                $route = $redirectStep ? route($redirectRoute, ['step' => $redirectStep]) : route($redirectRoute);
            } else {
                $route = route('user.dashboard');
            }
            
            return response()->json(['success' => true,'msg'=>'Account verified','route'=>$route]);
        } else {
            return response()->json(['error' => 'Invalid Code !']);
        }
    }


    public function newAuthenticationToken($email){ 
          $user = User::where('email', $email)->first();
          $gs=GeneralSetting::find(1);
          if($user && $gs->email_verification==1 && $user->is_email_verified!=1){
            $response=Helpers::send_verification_otp($user->email);
            if(isset($response['success'])){
              return response()->json(['success' => $response['success'] ]);
            }else{  return response()->json(['error' => $response['error'] ]); }
          }           
    }


}
