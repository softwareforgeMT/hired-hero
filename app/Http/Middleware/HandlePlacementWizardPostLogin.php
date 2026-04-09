<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Placement\PlacementWizardController;

class HandlePlacementWizardPostLogin
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
        $response = $next($request);

        // Check if user just logged in and has wizard session data
        if (auth()->check() && session()->has('placement_wizard_data')) {
            $controller = app(PlacementWizardController::class);
            $profile = $controller->saveSessionDataToProfile();
            
            // Redirect to step 6 (or the target step)
            $targetStep = session('placement_wizard_target_step', 6);
            session()->forget('placement_wizard_target_step');
            
            return redirect()->route('placement.wizard.step', ['step' => $targetStep]);
        }

        return $response;
    }
}
