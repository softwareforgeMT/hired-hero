<?php

namespace App\Jobs;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApplicationReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find all applications that need reminders
        $applicationsNeedingReminders = JobApplication::where('status', 'applied')
            ->where('reminder_sent', false)
            ->whereNotNull('applied_at')
            ->where('applied_at', '<=', now()->subDays(10))
            ->get();

        foreach ($applicationsNeedingReminders as $application) {
            try {
                // Send reminder email
                // Mail::send(new ApplicationReminderMailable($application));
                
                $application->markReminderSent();
                
                \Log::info("Reminder sent for application: {$application->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send reminder for application {$application->id}: " . $e->getMessage());
            }
        }
    }
}
