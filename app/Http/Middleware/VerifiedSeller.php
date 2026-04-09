<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class VerifiedSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        if(!Auth::check()){
            return redirect()->route('user.login'); 
        }
        elseif(auth()->user()->seller_verification=="verified")
        { 
            return $next($request); 
        }
        elseif(auth()->user()->seller_verification=="processing") {
            return redirect()->route('user.verify.seller','step-1')->with('info',"We are processing your information.");
        }     
        return redirect()->route('user.verify.seller','step-1')->with('info',"Seller Verification Required"); 
    }
}
