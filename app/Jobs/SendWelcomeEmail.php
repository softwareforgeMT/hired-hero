<?php

namespace App\Jobs;

use App\CentralLogics\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        \Log::info("Sending welcome email to: {$this->user->email}");
        // Helpers::welcomeEmail($this->user);
        
        // Send email to the admin about the new registration
        Helpers::welcomeEmailToAdmin($this->user);

        Helpers::welcomeEmailToUser($this->user);
    }
}
