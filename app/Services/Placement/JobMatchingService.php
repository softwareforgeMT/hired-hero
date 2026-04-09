<?php

namespace App\Services\Placement;

use App\Models\PlacementProfile;
use App\Models\JobMatch;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobMatchingService
{
    protected $sources = ['indeed', 'linkedin', 'glassdoor', 'workopolis'];

    /**
     * Generate job matches for a user profile
     */
    public function generateJobMatches(PlacementProfile $profile)
    {
        $selectedRoles = $profile->selected_roles ?? $profile->suggested_roles ?? [];
        $location = $profile->city ?? $profile->country;
        $jobMatches = [];

        foreach ($this->sources as $source) {
            $jobs = $this->fetchJobsFromSource($source, $selectedRoles, $location);
            $jobMatches = array_merge($jobMatches, $jobs);
        }

        // Limit to 20 matches (5 per source)
        $jobMatches = array_slice($jobMatches, 0, 20);

        return $jobMatches;
    }

    /**
     * Fetch jobs from external sources
     */
    private function fetchJobsFromSource($source, array $roles, $location)
    {
        $jobs = [];

        foreach ($roles as $role) {
            try {
                $sourceJobs = match($source) {
                    'indeed' => $this->fetchFromIndeed($role, $location),
                    'linkedin' => $this->fetchFromLinkedIn($role, $location),
                    'glassdoor' => $this->fetchFromGlassdoor($role, $location),
                    'workopolis' => $this->fetchFromWorkopolis($role, $location),
                    default => [],
                };

                $jobs = array_merge($jobs, $sourceJobs);
            } catch (\Exception $e) {
                Log::error("Error fetching from {$source}: " . $e->getMessage());
            }
        }

        return $jobs;
    }

    /**
     * Fetch from Indeed (mock implementation)
     */
    private function fetchFromIndeed($role, $location)
    {
        // In production, use Indeed API
        return $this->mockJobFetch('Indeed', $role, $location);
    }

    /**
     * Fetch from LinkedIn (mock implementation)
     */
    private function fetchFromLinkedIn($role, $location)
    {
        // In production, use LinkedIn API
        return $this->mockJobFetch('LinkedIn', $role, $location);
    }

    /**
     * Fetch from Glassdoor (mock implementation)
     */
    private function fetchFromGlassdoor($role, $location)
    {
        // In production, use Glassdoor API
        return $this->mockJobFetch('Glassdoor', $role, $location);
    }

    /**
     * Fetch from Workopolis (mock implementation)
     */
    private function fetchFromWorkopolis($role, $location)
    {
        // In production, use Workopolis API
        return $this->mockJobFetch('Workopolis', $role, $location);
    }

    /**
     * Mock job fetch for development
     */
    private function mockJobFetch($source, $role, $location)
    {
        $companies = ['Tech Corp', 'Global Solutions', 'Innovation Labs', 'Future Enterprises', 'Smart Systems'];
        $jobs = [];

        for ($i = 0; $i < 5; $i++) {
            $jobs[] = [
                'source' => strtolower($source),
                'job_title' => $role,
                'company_name' => $companies[array_rand($companies)],
                'location' => $location,
                'salary_min' => rand(40000, 80000),
                'salary_max' => rand(80000, 150000),
                'job_description' => "Looking for a talented {$role} professional to join our growing team.",
                'required_skills' => $this->getSkillsForRole($role),
                'job_url' => "https://" . strtolower($source) . ".com/jobs/sample-" . uniqid(),
                'posted_date' => now()->subDays(rand(1, 21)),
            ];
        }

        return $jobs;
    }

    /**
     * Get skills required for a specific role
     */
    private function getSkillsForRole($role)
    {
        $skillsByRole = [
            'data analyst' => ['sql', 'excel', 'tableau', 'python', 'statistics'],
            'web developer' => ['javascript', 'html', 'css', 'react', 'nodejs'],
            'python developer' => ['python', 'django', 'flask', 'sql', 'git'],
            'devops engineer' => ['aws', 'docker', 'kubernetes', 'ci/cd', 'linux'],
            'product manager' => ['product strategy', 'analytics', 'communication', 'project management'],
            'project manager' => ['project management', 'agile', 'communication', 'leadership'],
            'sales manager' => ['sales', 'crm', 'communication', 'negotiation', 'leadership'],
            'marketing manager' => ['marketing', 'analytics', 'content creation', 'seo'],
        ];

        return $skillsByRole[strtolower($role)] ?? ['communication', 'teamwork', 'problem-solving'];
    }

    /**
     * Calculate match score between profile and job
     */
    public function calculateMatchScore(PlacementProfile $profile, array $jobData)
    {
        $matchedSkills = [];
        $missingSkills = [];
        $score = 0;

        $profileSkills = array_map('strtolower', $profile->skills ?? []);
        $jobSkills = array_map('strtolower', $jobData['required_skills'] ?? []);

        // Calculate matched skills
        foreach ($jobSkills as $skill) {
            if (in_array($skill, $profileSkills)) {
                $matchedSkills[] = $skill;
                $score += 15; // 15 points per matched skill
            } else {
                $missingSkills[] = $skill;
            }
        }

        // Adjust for experience level
        $jobLevel = $this->estimateJobLevel($jobData);
        $profileLevel = $profile->job_level ?? 'entry';
        if ($jobLevel === $profileLevel) {
            $score += 10;
        }

        // Adjust for location match
        if ($this->isLocationMatch($profile, $jobData)) {
            $score += 10;
        }

        // Adjust for salary match
        if ($this->isSalaryMatch($profile, $jobData)) {
            $score += 10;
        }

        // Cap at 100
        $score = min($score, 100);

        return [
            'score' => $score,
            'matched_skills' => array_unique($matchedSkills),
            'missing_skills' => array_unique($missingSkills),
        ];
    }

    /**
     * Estimate job level from job description
     */
    private function estimateJobLevel($jobData)
    {
        $description = strtolower($jobData['job_description'] ?? '');

        if (preg_match('/(executive|director|vp|vice president|c-level)/i', $description)) {
            return 'executive';
        }
        if (preg_match('/(senior|principal|staff|lead|head)/i', $description)) {
            return 'senior';
        }
        if (preg_match('/(mid|intermediate|specialist|manager)/i', $description)) {
            return 'mid';
        }

        return 'entry';
    }

    /**
     * Check if location matches
     */
    private function isLocationMatch(PlacementProfile $profile, array $jobData)
    {
        $profileLocation = strtolower($profile->city ?? $profile->country ?? '');
        $jobLocation = strtolower($jobData['location'] ?? '');

        if ($profile->job_type === 'remote') {
            return true;
        }

        return str_contains($jobLocation, explode(',', $profileLocation)[0]);
    }

    /**
     * Check if salary matches
     */
    private function isSalaryMatch(PlacementProfile $profile, array $jobData)
    {
        if (!$profile->salary_min || !$jobData['salary_min']) {
            return true;
        }

        return $jobData['salary_min'] >= $profile->salary_min;
    }

    /**
     * Save job matches to database
     */
    public function saveJobMatches(PlacementProfile $profile, array $jobsList)
    {
        $savedMatches = [];

        foreach ($jobsList as $jobData) {
            $matchScore = $this->calculateMatchScore($profile, $jobData);

            $jobMatch = JobMatch::create([
                'user_id' => $profile->user_id,
                'placement_profile_id' => $profile->id,
                'job_title' => $jobData['job_title'],
                'company_name' => $jobData['company_name'],
                'source' => $jobData['source'],
                'job_description' => $jobData['job_description'] ?? null,
                'required_skills' => $jobData['required_skills'] ?? [],
                'location' => $jobData['location'],
                'job_url' => $jobData['job_url'],
                'salary_min' => $jobData['salary_min'] ?? null,
                'salary_max' => $jobData['salary_max'] ?? null,
                'match_score' => $matchScore['score'],
                'matched_skills' => $matchScore['matched_skills'],
                'missing_skills' => $matchScore['missing_skills'],
                'posted_date' => $jobData['posted_date'] ?? now(),
                'days_posted' => $this->calculateDaysPosted($jobData['posted_date'] ?? now()),
            ]);

            $savedMatches[] = $jobMatch;
        }

        return $savedMatches;
    }

    /**
     * Calculate days since job posting
     */
    private function calculateDaysPosted($postedDate)
    {
        return now()->diffInDays($postedDate);
    }
}
