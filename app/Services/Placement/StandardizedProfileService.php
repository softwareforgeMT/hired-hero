<?php

namespace App\Services\Placement;

use App\Models\PlacementProfile;

class StandardizedProfileService
{
    /**
     * Create a standardized profile from collected data
     */
    public function createStandardizedProfile(PlacementProfile $profile)
    {
        $standardized = [
            'job_preferences' => [
                'job_types' => [$profile->job_type],
                'salary_range' => [
                    'min' => $profile->salary_min,
                    'max' => $profile->salary_max,
                ],
                'job_level' => $profile->job_level,
            ],
            'location' => [
                'country' => $profile->country,
                'city' => $profile->city,
                'work_permit_status' => $profile->work_permit_status,
            ],
            'professional_profile' => [
                'industries' => $profile->industries ?? [],
                'skills' => $profile->skills ?? [],
                'years_experience' => $profile->years_experience,
            ],
            'experience' => [
                'past_companies' => $profile->past_companies ?? [],
                'past_sectors' => $profile->past_sectors ?? [],
            ],
            'target_roles' => $profile->selected_roles ?? $profile->suggested_roles ?? [],
            'timestamp' => now()->toIso8601String(),
        ];

        return $standardized;
    }

    /**
     * Update standardized profile
     */
    public function updateStandardizedProfile(PlacementProfile $profile)
    {
        $standardized = $this->createStandardizedProfile($profile);
        $profile->update(['standardized_profile' => $standardized]);
        return $standardized;
    }

    /**
     * Get standardized profile for targeting
     */
    public function getStandardizedProfile(PlacementProfile $profile)
    {
        return $profile->standardized_profile ?? $this->createStandardizedProfile($profile);
    }

    /**
     * Export profile data
     */
    public function exportProfile(PlacementProfile $profile)
    {
        return [
            'user_id' => $profile->user_id,
            'profile_id' => $profile->id,
            'created_at' => $profile->created_at,
            'completed_at' => $profile->completed_at,
            'standardized_profile' => $this->getStandardizedProfile($profile),
            'job_matches_count' => $profile->jobMatches()->count(),
            'applications_count' => $profile->jobApplications()->count(),
        ];
    }
}
