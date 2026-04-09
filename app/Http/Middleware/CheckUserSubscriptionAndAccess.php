<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CentralLogics\Helpers;

class CheckUserSubscriptionAndAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $feature)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Please login to access this feature.');
        }

        $userId = Auth::id();

        // Check if user has an active subscription
        if (!Helpers::hasActivePlan($userId)) {
            return redirect()->route('front.pricing')->with('error', 'You need an active subscription to access this feature.');
        }

        // Check if user has access to the requested feature
        if (!Helpers::hasAccess($userId, $feature)) {
            return redirect()->route('front.pricing')->with('error', 'You have reached the limit for this feature.');
        }

        return $next($request);
    }
}
