<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendAdminNewUserNotification
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Sends email to the admin when a new user registers
        Mail::raw(
            "New user registered on HiredHeroAI:\n\nName: {$user->name}\nEmail: {$user->email}\nCreated at: {$user->created_at}",
            function ($message) {
                $message->to(env('ADMIN_NOTIFICATION_ADDRESS'))
                        ->subject('New HiredHeroAI user signup');
            }
        );
    }
}
