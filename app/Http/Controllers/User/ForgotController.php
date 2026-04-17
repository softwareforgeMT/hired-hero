<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use Config;
use Hash;
use Illuminate\Support\Facades\Auth;
class ForgotController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForgotForm()
    {
      return view('user.auth.passwords.email');
    }


    public function forgot(Request $request)
    {
      
      $gs = GeneralSetting::findOrFail(1);
      $input =  $request->all();
      if (User::where('email', '=', $request->email)->count() > 0) {
      // user found
          $user = User::where('email', '=', $request->email)->firstOrFail();
          $autopass = Str::random(64);
          DB::table('password_resets')->insert(
                    ['email' => $request->email, 'token' => $autopass, 'created_at' => Carbon::now()]
                );

            if($gs->is_smtp == 1)
            {   
               $to = $user->email;
               $subject = "Password Reset!!";
               $msg = "Hello ".$user->name."!\nWe have recieved a Password Reset Request From you.\n Click on link to rest your Password \nThank you. \n <a href='".route('user.password.reset',$autopass)."'>Click Here </a>";

               $data = [
                            'to' => $to,
                            'subject' => $subject,
                            'body' => $msg,
                        ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);              
            }
            else
            {
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($request->email,$subject,$msg,$headers);            
            }
            Session::flash('message', 'We have e-mailed your password reset link!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
      }
      else{
        // user not found
        Session::flash('message', 'No Account Found With This Email.');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back(); 
      }  


    }



   public function getPassword($token) { 

    $user = DB::table('password_resets')
                        ->where('token',$token)
                        ->first();
                        
    if($user){
      return view('user.auth.passwords.reset', ['token' => $token,'email'=>$user->email]);
    }
    else{
      return view('errors.404');
    }

   }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
                ]);
        
        $updatePassword = DB::table('password_resets')
                        ->where(['email' => $request->email, 'token' => $request->token])
                        ->first();

          if(!$updatePassword){
            Session::flash('message', 'Invalid token!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
          }
      else{
          $user = User::where('email', $request->email)->first();
          $user->password=Hash::make($request->password);
          $user->update();          

         DB::table('password_resets')->where(['email'=> $request->email])->delete();
         Auth::guard('web')->login($user); 

         return redirect()->route('front.index')->with('success','Password Updated Successfully');
      }
      

    }


}