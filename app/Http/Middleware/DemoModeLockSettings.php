<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\CentralLogics\Helpers;

class DemoModeLockSettings
{
    /**
     * Handle an incoming request.
     *
     * In demo mode, form submissions are still allowed but are redirected to DemoController
     * which stores data in demo_settings table instead of real tables.
     * These requests are now handled via closures in routes/web.php
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware is no longer restrictive in demo mode
        // Requests are now handled intelligently in routes/web.php with closures
        // that route to DemoController when in demo mode
        
        return $next($request);
    }
}
