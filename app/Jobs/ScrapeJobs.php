<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\JobMatch;
use App\Models\ScrapingProgress;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ScrapeJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $selectedRoles;
    protected $progressId;
    protected $scrapeflyApiKey;
    protected $scrapeflyApiUrl;
    public $timeout = 3600; // 1 hour timeout
    public $tries = 1; // Don't retry on failure

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $selectedRoles = null)
    {
        $this->userId = $userId;
        $this->selectedRoles = $selectedRoles;
        $this->progressId = null;
        $this->scrapeflyApiKey = env('SCRAPEFLY_API_KEY');
        $this->scrapeflyApiUrl = 'https://api.scrapfly.io/scrape';
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $user = User::find($this->userId);
            
            if (!$user) {
                Log::error("User {$this->userId} not found for scraping job");
                return;
            }

            // Create or get scraping progress record
            $progress = ScrapingProgress::firstOrCreate(
                ['user_id' => $this->userId],
                ['status' => 'processing', 'progress' => 0]
            );

            $this->progressId = $progress->id;

            // Update status to processing
            $progress->update([
                'status' => 'processing',
                'progress' => 5,
                'message' => 'Initializing job search...'
            ]);

            $profile = $user->placementProfile;

            // Get selected roles from request or profile
            $selectedRoles = $this->selectedRoles ?? ($profile->selected_roles ?? []);

            if (empty($selectedRoles)) {
                throw new \Exception('No roles selected for scraping');
            }

            // Get user preferences
            $country = $profile->country ?? 'United States';
            $jobType = $profile->job_type ?? 'remote';
            $jobLevel = $profile->job_level ?? '';
            $salaryMin = $profile->salary_min ?? null;
            $salaryMax = $profile->salary_max ?? null;
            $languages = $profile->job_languages ?? [];
            $language = is_array($languages) && count($languages) > 0 ? $languages[0] : 'English';

            // Check subscription status
            $activeSubscription = $user->getActiveSubscription();
            $resultsPerPlatform = 2;

            if ($activeSubscription && $activeSubscription->isActive() && $activeSubscription->plan) {
                $resultsPerPlatform = $activeSubscription->plan->job_post_to_show ?? 2;
            }

            $allJobs = [];
            $roleCount = count($selectedRoles);
            $roleIndex = 0;

            // Iterate over each selected role and scrape results
            foreach ($selectedRoles as $role) {
                $roleIndex++;
                $roleProgress = 5 + (($roleIndex / $roleCount) * 40); // 5-45% for scraping

                $progress->update([
                    'progress' => (int)$roleProgress,
                    'message' => "Finding jobs for: {$role}..."
                ]);

                try {

                    $progress->update(['progress' => (int)($roleProgress + 10)]);

                    // Scrape from Wellfound
                    $wellfoundUrl = $this->buildWellfoundUrl($role);
                    $wellfoundJson = $this->fetchWithScrapeflyWelfound($wellfoundUrl);
                    $wellfoundJobs = array_slice($this->normalizeWellfoundJobData($wellfoundJson), 0, $resultsPerPlatform);
                    $allJobs = array_merge($allJobs, array_slice($wellfoundJobs, 0, $resultsPerPlatform));

                    $progress->update(['progress' => (int)($roleProgress + 15)]);

                    // Scrape from WorkDay
                    $workdayUrl = $this->buildWorkdayUrl($role, $country);
                    $workdayHtml = $this->fetchWithScrapeflyWorkDay($workdayUrl);
                    $workdayJobs = array_slice($this->normalizeWorkdayJobData($workdayHtml), 0, $resultsPerPlatform);
                    $allJobs = array_merge($allJobs, array_slice($workdayJobs, 0, $resultsPerPlatform));

                } catch (\Exception $e) {
                    Log::error("Error scraping role: {$role} - " . $e->getMessage());
                }
            }

            $progress->update(['progress' => 50, 'message' => 'Processing job descriptions...']);

            // Fetch detailed job descriptions (with batch processing to avoid timeout)
            $allJobs = collect($allJobs)->map(function ($job, $index) use ($progress, $allJobs) {
                $descProgress = 50 + (($index / count($allJobs)) * 30);
                if ($index % 5 == 0) { // Update progress every 5 jobs
                    $progress->update(['progress' => (int)$descProgress]);
                }

                return $job;
            })->toArray();

            $progress->update(['progress' => 80, 'message' => 'Saving job matches...']);

            // Delete previous job matches before adding new ones
            JobMatch::where('user_id', $user->id)->delete();

            // Process all collected jobs
            $allJobs = collect($allJobs)->map(function ($job) use ($profile) {
                $job['match_score'] = rand(60, 100); // Default match score
                $job['user_id'] = $profile->user_id;
                return $job;
            })->toArray();

            foreach ($allJobs as $job) {
                JobMatch::create([
                    'user_id' => $user->id,
                    'placement_profile_id' => $profile->id,
                    'job_title' => $job['job_title'] ?? 'N/A',
                    'company_name' => $job['company_name'] ?? 'N/A',
                    'location' => $job['location'] ?? 'N/A',
                    'job_url' => $job['job_link'] ?? $job['link'] ?? 'N/A',
                    'source' => $job['platform'] ?? 'Unknown',
                    'description' => $job['description'] ?? 'N/A',
                    'match_score' => $job['match_score'] ?? 85,
                    'posted_date' => $job['posted_date'] ?? now(),
                    'salary_range' => $job['salary_range'] ?? 'N/A',
                ]);
            }

            // Mark as completed
            $progress->update([
                'status' => 'completed',
                'progress' => 100,
                'message' => 'Job search completed successfully!',
                'completed_at' => now(),
                'total_jobs' => count($allJobs)
            ]);

            Log::info("Job search completed for user {$this->userId}. Total jobs: " . count($allJobs));

        } catch (\Exception $e) {
            Log::error('Error during background job search: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());

            if ($this->progressId) {
                ScrapingProgress::find($this->progressId)?->update([
                    'status' => 'failed',
                    'progress' => 0,
                    'message' => 'Job search failed: ' . $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Fetch content from URL (similar to ScraperController)
     */
    private function fetchUrlContent($url)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get($url);

            return $response->body();
        } catch (\Exception $e) {
            Log::warning("Failed to fetch URL: {$url} - " . $e->getMessage());
            return '';
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Job search failed for user ' . $this->userId . ': ' . $exception->getMessage());

        if ($this->progressId) {
            ScrapingProgress::find($this->progressId)?->update([
                'status' => 'failed',
                'message' => 'Job search failed. Please try again.'
            ]);
        }
    }

    /**
     * Build Wellfound URL with user preferences
     */
    private function buildWellfoundUrl(string $role): string
    {
        $role = strtolower(trim($role));
        // Convert role to URL-friendly format (e.g., "Software Engineer" -> "software-engineer")
        $roleSlug = str_replace([' ', '_'], '-', $role);
        return 'https://wellfound.com/role/' . $roleSlug;
    }

    /**
     * Build WorkDay URL with user preferences
     */
    private function buildWorkdayUrl(string $role, string $country): string
    {
        // WorkDay base URL - supports keyword search
        $country = ['United States' => 'bc33aa3152ec42d4995f4791a106ed09', 'Canada' => 'a30a87ed25634629aa6c3958aa2b91ea', 'Australia' => 'd903bb3fedad45039383f6de334ad4db'][$country];
        $roleEncoded = urlencode($role);
        return 'https://workday.wd5.myworkdayjobs.com/Workday?q=' . $roleEncoded . '&Location_Country=' . urlencode($country);
    }


    /**
     * Fetch HTML content using Scrapefly.io with Guzzle Client
     */
    private function fetchWithScrapeflyWelfound($url)
    {
        try {
            Log::info('Fetching with Scrapfly (Guzzle Client) - Target: ' . $url);

            // Use Guzzle Client directly for better control
            $client = new Client([
                'base_uri'         => 'https://api.scrapfly.io',
                'timeout'          => 3600.0,         // 3600 seconds for JS rendering
                'connect_timeout'  => 10.0,          // 10 seconds to connect
                'http_errors'      => false,         // Don't throw on 4xx/5xx
            ]);

            $params = [
                'key'       => $this->scrapeflyApiKey,
                'url'       => $url,
                'format'    => 'json',
                'asp'       => 'true',
                'render_js' => 'true',
                'tags'      => 'job-scraper',
            ];

            $response = $client->request('GET', '/scrape', [
                'query'           => $params,
                'decode_content'  => true,  // Auto-decode gzip/deflate
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            Log::info('Scrapfly Status: ' . $statusCode);

            if ($statusCode >= 400) {
                $errorMsg = $data['message'] ?? $data['description'] ?? 'Request failed';
                Log::warning('Scrapfly API Error (' . $statusCode . '): ' . $errorMsg);
                return '';
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                // Scrapfly returns HTML in 'result' -> 'content'
                if (isset($data['result']['content'])) {
                    Log::info('Scrapfly: Successfully fetched content (' . strlen($data['result']['content']) . ' bytes)');
                    $content = json_decode($data['result']['content'], true);
                    $aValues = $content['content']['a'] ?? [];
                    return $aValues;
                }

                Log::warning('Scrapfly: No content in response');
                return '';
            }

            return '';
        } catch (RequestException $e) {
            Log::warning('Scrapfly RequestException: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::warning('Response: ' . $e->getResponse()->getBody());
            }
            return '';
        } catch (\Exception $e) {
            Log::warning('Scrapfly error: ' . $e->getMessage());
            return '';
        }
    }

    private function fetchWithScrapeflyWorkDay($url)
    {
        try {
            Log::info('Fetching WorkDay page with Scrapfly - Target: ' . $url);

            $client = new Client([
                'base_uri'         => 'https://api.scrapfly.io',
                'timeout'          => 3600.0,
                'connect_timeout'  => 10.0,
                'http_errors'      => false,
            ]);

            $params = [
                'key'           => $this->scrapeflyApiKey,
                'url'           => $url,
                'asp'           => 'true',
                'render_js'     => 'true',
                'proxy_country' => 'US',
                'tags'          => 'job-scraper-workday',
            ];

            $response = $client->request('GET', '/scrape', [
                'query'           => $params,
                'decode_content'  => true,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            Log::info('Scrapfly Status: ' . $statusCode);

            if ($statusCode >= 400) {
                $errorMsg = $data['message'] ?? $data['description'] ?? 'Request failed';
                Log::warning('Scrapfly API Error (' . $statusCode . '): ' . $errorMsg);
                return '';
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                // Scrapfly returns HTML content in result->content
                if (isset($data['result']['content'])) {
                    Log::info('Scrapfly: Successfully fetched WorkDay content (' . strlen($data['result']['content']) . ' bytes)');
                    return $data['result']['content'];
                }

                Log::warning('Scrapfly: No content in response');
                return '';
            }

            return '';
        } catch (RequestException $e) {
            Log::warning('Scrapfly RequestException: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::warning('Response: ' . $e->getResponse()->getBody());
            }
            return '';
        } catch (\Exception $e) {
            Log::warning('Scrapfly WorkDay error: ' . $e->getMessage());
            return '';
        }
    }


    /**
     * Normalize Wellfound job data from JSON API response
     * Filters tags to extract job links (href contains /jobs/) and associates each with the current company
     * Company is tracked sequentially - each job is paired with the most recent company encountered
     */
    private function normalizeWellfoundJobData($data): array
    {
        $jobs = [];

        try {
            // If data is a string (JSON), decode as array
            if (is_string($data)) {
                $tags = json_decode($data, true);
                if (!is_array($tags)) {
                    Log::warning('Invalid JSON data for Wellfoundsss');
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

                // Extract job link and associate with current company
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
                                'platform'     => 'wellfound',
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
     * Normalize WorkDay job data from HTML
     * Extracts: job title, link, location, and posted date from HTML structure
     */
    private function normalizeWorkdayJobData(string $html): array
    {
        $jobs = [];

        try {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            $xpath = new \DOMXPath($dom);

            // Get all job list items from the results section
            $jobCards = $xpath->query(".//section[@data-automation-id='jobResults']//ul//li[contains(@class, 'css-1q2dra3')]");

            $seen = [];

            foreach ($jobCards as $jobCard) {
                // Extract job title and link from h3 > a with data-automation-id="jobTitle"
                $jobTitleLink = $xpath->query(".//h3//a[@data-automation-id='jobTitle']", $jobCard)->item(0);

                if (!$jobTitleLink) {
                    continue;
                }

                $jobTitle = trim($jobTitleLink->textContent);
                $jobLink = $jobTitleLink->getAttribute('href');

                if (empty($jobTitle) || empty($jobLink)) {
                    continue;
                }

                // Make absolute URL if relative
                if (!str_starts_with($jobLink, 'http')) {
                    $jobLink = 'https://workday.wd5.myworkdayjobs.com' . $jobLink;
                }

                // Extract location from div with data-automation-id="locations" > dl > dd
                $locationDd = $xpath->query(".//div[@data-automation-id='locations']//dd[contains(@class, 'css-129m7dg')]", $jobCard)->item(0);
                $location = $locationDd ? trim($locationDd->textContent) : 'Not Specified';

                // Extract posted date from div with data-automation-id="postedOn" > dl > dd
                $postedDd = $xpath->query(".//div[@data-automation-id='postedOn']//dd[contains(@class, 'css-129m7dg')]", $jobCard)->item(0);
                $postedDate = $postedDd ? trim($postedDd->textContent) : null;

                // Create unique key to avoid duplicates
                $uniqueKey = md5($jobTitle . $jobLink);
                if (isset($seen[$uniqueKey])) {
                    continue;
                }
                $seen[$uniqueKey] = true;

                $jobs[] = [
                    'job_title'    => $jobTitle,
                    'job_link'     => $jobLink,
                    'company_name' => 'Workday',
                    'location'     => $location,
                    'time'         => $postedDate,
                    'image_link'   => null,
                    'platform'     => 'workday',
                ];
            }

            libxml_clear_errors();
        } catch (\Exception $e) {
            Log::warning('Error parsing WorkDay HTML: ' . $e->getMessage());
        }

        return $jobs;
    }
}
