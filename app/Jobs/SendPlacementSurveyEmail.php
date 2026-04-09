<?php

namespace App\Jobs;

use App\Models\PlacementProfile;
use App\Models\PlacementSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPlacementSurveyEmail implements ShouldQueue
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
        // Find profiles that are 14 days old
        $profilesAt14Days = PlacementProfile::where('is_completed', true)
            ->where('completed_at', '<=', now()->subDays(14))
            ->where('completed_at', '>=', now()->subDays(14)->subHours(12))
            ->get();

        foreach ($profilesAt14Days as $profile) {
            try {
                // Check if survey already exists for this profile
                $existingSurvey = PlacementSurvey::where('placement_profile_id', $profile->id)
                    ->where('days_after_completion', 14)
                    ->first();

                if (!$existingSurvey) {
                    // Create survey
                    $survey = PlacementSurvey::create([
                        'user_id' => $profile->user_id,
                        'placement_profile_id' => $profile->id,
                        'days_after_completion' => 14,
                        'survey_type' => 'initial-progress',
                    ]);

                    // Send email
                    // Mail::send(new PlacementSurveyMailable($survey));
                    
                    $survey->markEmailSent();
                    
                    \Log::info("Survey email sent for profile: {$profile->id}");
                }
            } catch (\Exception $e) {
                \Log::error("Failed to send survey for profile {$profile->id}: " . $e->getMessage());
            }
        }

        // Also check 30 and 60 day surveys
        $this->sendSurveyAt($profilesAt14Days->pluck('id'), 30, 'interview-feedback');
        $this->sendSurveyAt($profilesAt14Days->pluck('id'), 60, 'conversion');
    }

    /**
     * Send survey at specific days
     */
    private function sendSurveyAt($profileIds, $days, $surveyType)
    {
        $profilesAtDays = PlacementProfile::whereIn('id', $profileIds)
            ->where('is_completed', true)
            ->where('completed_at', '<=', now()->subDays($days))
            ->where('completed_at', '>=', now()->subDays($days)->subHours(12))
            ->get();

        foreach ($profilesAtDays as $profile) {
            $existingSurvey = PlacementSurvey::where('placement_profile_id', $profile->id)
                ->where('days_after_completion', $days)
                ->first();

            if (!$existingSurvey) {
                PlacementSurvey::create([
                    'user_id' => $profile->user_id,
                    'placement_profile_id' => $profile->id,
                    'days_after_completion' => $days,
                    'survey_type' => $surveyType,
                ]);
            }
        }
    }
}
