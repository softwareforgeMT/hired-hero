<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class CaptureReferral
{
    public function handle(Request $request, Closure $next)
    {
        $reff = $request->query('reff');

        if ($reff) {
            $referrer = User::where('affiliate_code', $reff)->first();

            if ($referrer) {

                // don't overwrite if cookie already exists
                if (!$request->cookie('referrer_code')) {
                    Session::put('referrer_code', $reff);
                    Session::put('referrer_user_id', $referrer->id);

                    // show message on next page load
                    Session::flash('referral_banner', true);

                    // store cookie for 30 days
                    Cookie::queue('referrer_code', $reff, 60 * 24 * 30);
                }
            }
        }

        return $next($request);
    }
}
