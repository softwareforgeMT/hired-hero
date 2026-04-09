<?php

namespace App\Jobs;

use App\Models\PlacementProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleFreeAccessExpiration implements ShouldQueue
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
        // Find profiles where free access is expiring today
        $profilesExpiringToday = PlacementProfile::where('is_completed', true)
            ->where('free_access_expires_at', '<=', now())
            ->where('has_active_placement_subscription', false)
            ->whereDate('free_access_expires_at', '=', now()->toDateString())
            ->get();

        foreach ($profilesExpiringToday as $profile) {
            try {
                // Send email notification about expiration
                // Mail::send(new FreeAccessExpirationMailable($profile));
                
                \Log::info("Free access expiration notification sent for profile: {$profile->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send expiration notice for profile {$profile->id}: " . $e->getMessage());
            }
        }

        // Find profiles where free access has expired
        $profilesExpired = PlacementProfile::where('is_completed', true)
            ->where('free_access_expires_at', '<', now())
            ->where('has_active_placement_subscription', false)
            ->get();

        foreach ($profilesExpired as $profile) {
            try {
                // Update profile status - they can no longer access job matches
                \Log::info("Free access has expired for profile: {$profile->id}");
                
                // Send upsell email
                // Mail::send(new SubscriptionUpsellMailable($profile));
            } catch (\Exception $e) {
                \Log::error("Failed to handle expiration for profile {$profile->id}: " . $e->getMessage());
            }
        }
    }
}
