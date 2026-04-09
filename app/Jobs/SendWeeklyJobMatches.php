<?php

namespace App\Jobs;

use App\Mail\WeeklyJobMatches;
use App\Models\User;
use App\Models\JobMatch;
use App\Models\JobMatchEmailLog;
use App\Traits\ScrapesWithScrapfly;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendWeeklyJobMatches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ScrapesWithScrapfly;

    public $timeout = 300; // 5 minutes timeout

    public function handle(): void
    {
        try {
            Log::info('Starting Weekly Job Matches Email Task');

            // Get all users with active placements
            $users = User::has('placementProfile')
                ->where('email_verified_at', '!=', null)
                ->get();

            $sentCount = 0;
            $skippedCount = 0;

            foreach ($users as $user) {
                try {
                    // Check if user already received email this week
                    if ($this->hasReceivedEmailThisWeek($user)) {
                        Log::info("User {$user->id} already received email this week, skipping");
                        $skippedCount++;
                        continue;
                    }

                    $profile = $user->placementProfile;

                    if (!$profile || !$profile->selected_roles || count($profile->selected_roles) === 0) {
                        Log::info("User {$user->id} has no selected roles, skipping");
                        $skippedCount++;
                        continue;
                    }

                    // Select ONE random role from user's selected roles
                    $selectedRole = $profile->selected_roles[array_rand($profile->selected_roles)];

                    Log::info("Fetching Wellfound jobs for user {$user->id}, role: {$selectedRole}");

                    // Fetch jobs from Wellfound API using Scrapfly
                    $wellfoundUrl = $this->buildWellfoundUrl($selectedRole);
                    $wellfoundData = $this->fetchWithScrapeflyWelfound($wellfoundUrl);
                    
                    if (empty($wellfoundData)) {
                        Log::warning("No data fetched from Wellfound for role: {$selectedRole}");
                        $skippedCount++;
                        continue;
                    }

                    // Normalize the Wellfound jobs
                    $jobs = array_slice(
                        $this->normalizeWellfoundJobData($wellfoundData),
                        0,
                        4  // Limit to 4 jobs
                    );

                    if (empty($jobs)) {
                        Log::info("No jobs found for {$selectedRole} from Wellfound, skipping user {$user->id}");
                        $skippedCount++;
                        continue;
                    }

                    Log::info("Found " . count($jobs) . " jobs for role {$selectedRole}");

                    // Send email with fetched jobs
                    Mail::send(new WeeklyJobMatches($user, $profile, $selectedRole, $jobs));

                    // Log the email send
                    JobMatchEmailLog::create([
                        'user_id' => $user->id,
                        'placement_profile_id' => $profile->id,
                        'selected_role' => $selectedRole,
                        'job_count' => count($jobs),
                        'job_ids' => array_column($jobs, 'job_link'),  // Store job links as reference
                        'sent_at' => now(),
                        'last_sent_week' => now(),
                    ]);

                    Log::info("Sent weekly job matches email to user {$user->id} for role: {$selectedRole}");
                    $sentCount++;

                } catch (\Exception $e) {
                    Log::error("Error sending email to user {$user->id}: " . $e->getMessage());
                    continue;
                }
            }

            Log::info("Weekly Job Matches Task Completed - Sent: {$sentCount}, Skipped: {$skippedCount}");

        } catch (\Exception $e) {
            Log::error('Error in SendWeeklyJobMatches job: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Normalize Wellfound job data from JSON API response
     * Filters tags to extract job links (href contains /jobs/) and associates each with the current company
     * Company is tracked sequentially - each job is paired with the most recent company encountered
     */
    public function normalizeWellfoundJobData($data): array
    {
        $jobs = [];
        
        try {
            // If data is a string (JSON), decode as array
            if (is_string($data)) {
                $tags = json_decode($data, true);
                if (!is_array($tags)) {
                    Log::warning('Invalid JSON data for Wellfound');
                    return [];
                }
            } else {
                $tags = $data;
            }
            
            if (!is_array($tags)) {
                return [];
            }
            
            $currentCompany = 'Unknown';
            $seen = [];
            
            // Sequential pass: Track current company and associate jobs with it
            foreach ($tags as $tag) {
                if (!is_array($tag) || ($tag['tag'] ?? null) !== 'a') {
                    continue;
                }
                
                $href = $tag['attributes']['href'] ?? null;
                
                if (!$href) {
                    continue;
                }
                
                // Update current company when encountering a company link with h2 child
                if (strpos($href, '/company/') !== false && isset($tag['children']) && is_array($tag['children'])) {
                    foreach ($tag['children'] as $child) {
                        if (is_array($child) && ($child['tag'] ?? null) === 'h2') {
                            $currentCompany = trim($child['text'] ?? 'Unknown');
                            break;
                        }
                    }
                }
                
                // Extract job and associate with currentCompany
                if (strpos($href, '/jobs/') !== false) {
                    $jobTitle = trim($tag['text'] ?? '');
                    
                    if ($jobTitle) {
                        $uniqueKey = md5($jobTitle . $href);
                        
                        if (!isset($seen[$uniqueKey])) {
                            $jobs[] = [
                                'job_title'    => $jobTitle,
                                'job_link'     => $href,
                                'company_name' => $currentCompany,
                                'location'     => 'Remote',
                                'time'         => null,
                                'image_link'   => null,
                                'platform'     => 'Wellfound',
                            ];
                            $seen[$uniqueKey] = true;
                        }
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::warning('Error parsing Wellfound data: ' . $e->getMessage());
        }
        
        return $jobs;
    }

    /**
     * Check if user already received email this week (Monday to Sunday)
     */
    private function hasReceivedEmailThisWeek(User $user): bool
    {
        // Get this week's start (Monday)
        $weekStart = Carbon::now()->startOfWeek();
        // Get this week's end (Sunday)
        $weekEnd = Carbon::now()->endOfWeek();

        $recentEmail = JobMatchEmailLog::where('user_id', $user->id)
            ->whereBetween('sent_at', [$weekStart, $weekEnd])
            ->exists();

        return $recentEmail;
    }
}
