<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Http\Controllers\Placement\PlacementWizardController;

class HandlePlacementWizardAfterLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Check if user has placement wizard session data
        if (session()->has('placement_wizard_data')) {
            $controller = app(PlacementWizardController::class);
            $profile = $controller->saveSessionDataToProfile();
            
            // Store the target step for redirect after login
            session(['placement_wizard_redirect' => true]);
        }
    }
}
