<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;
use App\Spiders\ExtractWebPage;
use App\Models\PlacementProfile;
use App\Models\User;
use App\Models\ResumeSubscription;
use App\Models\JobMatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ScraperController extends Controller
{
    protected $scrapeflyApiKey;
    protected $scrapeflyApiUrl;

    public function __construct()
    {
        $this->scrapeflyApiKey = env('SCRAPEFLY_API_KEY');
        $this->scrapeflyApiUrl = 'https://api.scrapfly.io/scrape';
    }

    public function scrape(Request $request)
    {
        try
        {
            $startTime = microtime(true);
            $maxExecutionTime = 25; // Leave 5 seconds buffer

            $user = auth()->user();
            $profile = $user->placementProfile;

            // Get selected roles from request or profile
            $selectedRoles = $request->input('selected_roles', $profile->selected_roles ?? []);

            if (empty($selectedRoles)) {
                return response()->json(['error' => 'No roles selected'], 400);
            }

            // Get user preferences
            $country = $profile->country ?? 'United States';
            $jobType = $profile->job_type ?? 'remote';
            $jobLevel = $profile->job_level ?? '';
            $salaryMin = $profile->salary_min ?? null;
            $salaryMax = $profile->salary_max ?? null;
            $languages = $profile->job_languages ?? [];
            $language = is_array($languages) && count($languages) > 0 ? $languages[0] : 'English';

            // Check subscription status for result limits based on job_post_to_show feature
            $activeSubscription = $user->getActiveSubscription();
            $resultsPerPlatform = 2; // Default for free/no subscription

            if ($activeSubscription && $activeSubscription->isActive() && $activeSubscription->plan) {
                // Get job_post_to_show from subscription plan's access_section
                $accessSection = $activeSubscription->plan->access_section;
                
                if (is_array($accessSection) && isset($accessSection['jobMatches']['job_post_to_show'])) {
                    $resultsPerPlatform = (int) $accessSection['jobMatches']['job_post_to_show'];
                } elseif (is_string($accessSection)) {
                    $decoded = json_decode($accessSection, true);
                    if (isset($decoded['jobMatches']['job_post_to_show'])) {
                        $resultsPerPlatform = (int) $decoded['jobMatches']['job_post_to_show'];
                    }
                }
            }

            $allJobs = [];

            // Iterate over each selected role and scrape results
            foreach ($selectedRoles as $role) {
                // Check time remaining
                $elapsedTime = microtime(true) - $startTime;
                if ($elapsedTime > $maxExecutionTime) {
                    Log::warning('Scraper timeout approaching, skipping remaining roles');
                    break;
                }

                // Wellfound Jobs (using Scrapefly.io)
                $wellfoundUrl = $this->buildWellfoundUrl($role);
                $htmlWellfound = $this->fetchWithScrapeflyWelfound($wellfoundUrl);
                // $htmlWellfound = file_get_contents(public_path('test.json')); 
                $wellfoundJobs = array_slice($this->normalizeWellfoundJobData($htmlWellfound), 0, $resultsPerPlatform);
                // WorkDay Jobs (using Scrapefly.io)
                $workdayUrl = $this->buildWorkdayUrl($role, $country);
                $htmlWorkday = $this->fetchWithScrapeflyWorkDay($workdayUrl);
                $workdayJobs = array_slice($this->normalizeWorkdayJobData($htmlWorkday), 0, );

                // Merge results for this role (Wellfound + WorkDay only)
                $roleJobs = array_merge($wellfoundJobs ?? [], $workdayJobs ?? []) ?? [];
                $allJobs = array_merge($allJobs, $roleJobs);
            }

            // Fetch detailed job descriptions for each job (with time limit)
            $allJobs = collect($allJobs)->map(function ($job) use ($startTime, $maxExecutionTime) {
                // Check time remaining before fetching each description
                $elapsedTime = microtime(true) - $startTime;
                if ($elapsedTime > $maxExecutionTime) {
                    $job['job_description'] = 'No description available.';
                    return $job;
                }

                try {
                    $description = $this->fetchJobDescription($job['job_link'], $job['platform']);
                    $job['job_description'] = $description ?? 'No description available.';
                } catch (\Exception $e) {
                    Log::warning('Failed to fetch job description for ' . $job['job_link'] . ': ' . $e->getMessage());
                    $job['job_description'] = 'No description available.';
                }
                return $job;
            })->toArray();

            // Delete previous job matches before adding new ones
            JobMatch::where('user_id', $user->id)
                ->where('placement_profile_id', $profile->id)
                ->delete();

            // Process all collected jobs
            $allJobs = collect($allJobs)->map(function ($job) use ($profile) {
                $job['match_score'] = rand(60, 100);
                $job['required_skills'] = $profile->skills ?? [];
                $job['matched_skills'] = array_slice($profile->skills ?? [], 0, rand(1, count($profile->skills ?? [])));
                $job['missing_skills'] = array_diff($profile->skills ?? [], $job['matched_skills']);
                return $job;
            })->toArray();

            foreach ($allJobs as $job) {
                JobMatch::create([
                    'user_id' => $user->id,
                    'placement_profile_id' => $profile->id,
                    'job_url' => $job['job_link'],
                    'job_title' => $job['job_title'],
                    'image_url' => $job['image_link'] ?? null,
                    'company_name' => $job['company_name'],
                    'source' => strtolower($job['platform']),
                    'location' => $job['location'],
                    'job_description' => $job['job_description'] ?? null,
                    'required_skills' => $job['required_skills'] ?? [],
                    'match_score' => $job['match_score'],
                    'matched_skills' => $job['matched_skills'],
                    'missing_skills' => $job['missing_skills'],
                    'posted_date' => $job['time'] ?? now(),
                    'days_posted' => 0,
                ]);
            }

            return response()->json($allJobs);
        } catch (\Exception $e) {
            Log::error('Error during scraping: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while scraping. Please try again later.'], 500);
        }
       
    }

    private function buildIndeedUrl($role, $location, $jobType, $jobLevel, $salaryMin, $salaryMax, $language)
    {
        $baseUrl = 'https://www.indeed.com/jobs';

        $params = [
            'q' => $role,
            'l' => $location,
        ];

        $scParts = [];

        // Experience level
        if ($jobLevel && $jobLevel !== 'no-preference') {
            $levelMap = [
                'entry'     => 'ENTRY_LEVEL',
                'mid'       => 'MID_LEVEL',
                'senior'    => 'SENIOR_LEVEL',
                'executive' => 'EXECUTIVE_LEVEL',
            ];
            if (isset($levelMap[$jobLevel])) {
                $scParts[] = 'explvl(' . $levelMap[$jobLevel] . ')';
            }
        }

        // Job Type (Indeed uses attr codes, example: Contract = NJXCK)
        if ($jobType && $jobType !== 'no-preference') {
            $jobTypeAttrMap = [
                'contract'   => 'NJXCK',
            ];

            if (isset($jobTypeAttrMap[$jobType])) {
                $scParts[] = 'attr(' . $jobTypeAttrMap[$jobType] . ')';
            }
        }


        if (!empty($scParts)) {
            $params['sc'] = '0kf:' . implode('', $scParts) . ';';
        }

        // Salary (Indeed uses salaryType like "$50,000+")
        if ($salaryMin) {
            $params['salaryType'] = '$' . number_format((int)$salaryMin) . '+';
        }

        if ($language && $language !== 'English') {
            $params['lang'] = strtolower($language);
        }
        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * Build LinkedIn URL with user preferences
     */
    private function buildLinkedInUrl(
        string $role,
        string $country,
        ?string $jobLevel = null,
        ?string $language = null,
        ?string $workplaceType = null,
        ?string $jobType = null,
        ?string $timePosted = null,
        bool $easyApply = false,
        string $sortBy = 'R'
    ): string {
        try {
            $role = trim($role);
            $country = trim($country);
            $language = $language ? trim($language) : null;

            // LinkedIn doesn't reliably support "language" as a search param.
            // Best practice: include it in keywords (e.g., "Backend Developer Laravel")
            $keywords = $role;
            if ($language && strtolower($language) !== 'english') {
                $keywords .= ' ' . $language;
            }

            // Country -> geoId (optional)
        $geoIdMap = [
            'United States' => '103644278',
            'Canada' => '102393600',
            'United Kingdom' => '101165590',
            'Australia' => '100994043',
            'India' => '102713980',
            'Germany' => '101282230',
            'France' => '103933857',
            'Netherlands' => '102890719',
            'Sweden' => '102838331',
            'Singapore' => '102252792',
            'Japan' => '101909779',
            'China' => '102454443',
            'Brazil' => '102277800',
            'Mexico' => '104738315',
            'New Zealand' => '104012617',
            'South Korea' => '104208460',
            'UAE' => '104021052',
            'Saudi Arabia' => '103663137',
            'Ireland' => '104449300',
            'Spain' => '104134254',
            'Italy' => '103350018',
            'Poland' => '103809266',
        ];

        $params = [
            'keywords' => $keywords,
            'location' => $country,
            'sortBy'   => $sortBy,
        ];

        // Only add geoId if you have it (don’t default to US)
        if (isset($geoIdMap[$country])) {
            $params['geoId'] = $geoIdMap[$country];
        }

        // Experience Level (LinkedIn: f_E)
        // 1=Internship, 2=Entry, 3=Associate, 4=Mid-Senior, 5=Director, 6=Executive
        if ($jobLevel) {
            $levelMap = [
                'internship'   => '1',
                'entry'  => '2',
                'associate'    => '3',
                'mid'    => '4',
                'senior' => '4',
                'executive'    => '6',
            ];
            if (isset($levelMap[$jobLevel])) {
                $params['f_E'] = $levelMap[$jobLevel];
            }
        }

        // Workplace Type (LinkedIn: f_WT) 1=On-site, 2=Remote, 3=Hybrid
        if ($workplaceType) {
            $wtMap = [
                'in-person' => '1',
                'remote' => '2',
                'hybrid' => '3',
            ];
            if (isset($wtMap[$workplaceType])) {
                $params['f_WT'] = $wtMap[$workplaceType];
            }
        }

        // Job Type (LinkedIn: f_JT)
        if ($jobType) {
            $jtMap = [
                'full-time'  => 'F',
                'part-time'  => 'P',
                'contract'   => 'C',
                'temporary'  => 'T',
                'internship' => 'I',

            ];
            if (isset($jtMap[$jobType])) {
                $params['f_JT'] = $jtMap[$jobType];
            }
        }

        // Time Posted (LinkedIn: f_TPR)
        if ($timePosted) {
            $tprMap = [
                '24h'   => 'r86400',
                'week'  => 'r604800',
                'month' => 'r2592000',
            ];
            if (isset($tprMap[$timePosted])) {
                $params['f_TPR'] = $tprMap[$timePosted];
            }
        }

        // Easy Apply (LinkedIn: f_AL)
        if ($easyApply) {
            $params['f_AL'] = 'true';
        }

            $queryString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
            return 'https://www.linkedin.com/jobs/search/?' . $queryString;
        } catch (\Exception $e) {
            // Log error and return a default URL
            Log::error('Error building LinkedIn URL: ' . $e->getMessage());
            return 'https://www.linkedin.com/jobs/search/?keywords=' . urlencode($role);
        }
    }

    function normalizeLinkedInJobData(string $html): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        $jobs = [];

        $nodes = $xpath->query("//li");

        foreach ($nodes as $node) {
            $jobs[] = [
                'job_link' => $xpath->evaluate("string(.//a[contains(@class, 'base-card__full-link')]/@href)", $node),
                'job_title' => trim($xpath->evaluate("string(.//h3[contains(@class, 'base-search-card__title')])", $node)),

                'company_name' => trim($xpath->evaluate("string(.//h4[contains(@class, 'base-search-card__subtitle')]//a)", $node)),

                'location' => trim($xpath->evaluate("string(.//span[contains(@class, 'job-search-card__location')])", $node)),

                'time' => trim($xpath->evaluate("string(.//time[contains(@class, 'job-search-card__listdate')])", $node)),

                'image_link' => $xpath->evaluate("string(.//img[contains(@class, 'artdeco-entity-image')]/@data-delayed-url)", $node)
                    ?: $xpath->evaluate("string(.//img[contains(@class, 'artdeco-entity-image')]/@src)", $node),
                'platform' => 'LinkedIn'
            ];
        }

        libxml_clear_errors();
        return array_filter($jobs, fn($job) => !empty($job['job_title']));
    }

    private function normalizeIndeedJobData(string $html): array
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        $xpath = new \DOMXPath($dom);

        $jobs = [];

        // First get the left pane safely
        $leftPane = $xpath->query("//div[contains(@class, 'jobsearch-LeftPane')]")->item(0);

        if (!$leftPane) {
            libxml_clear_errors();
            return [];
        }

        // Actual visible job cards only
        $jobCards = $xpath->query(
            ".//div[contains(@class, 'result') and .//a[@data-jk] and not(@aria-hidden='true')]",
            $leftPane
        );

        $seen = [];

        foreach ($jobCards as $jobCard) {
            $title = trim($xpath->evaluate("string(.//h2[contains(@class, 'jobTitle')]//span)", $jobCard));
            $company = trim($xpath->evaluate("string(.//span[@data-testid='company-name'])", $jobCard));
            $location = trim($xpath->evaluate("string(.//div[@data-testid='text-location'])", $jobCard));
            $relativeLink = trim($xpath->evaluate("string(.//h2[contains(@class, 'jobTitle')]//a/@href)", $jobCard));
            $jk = trim($xpath->evaluate("string(.//h2[contains(@class, 'jobTitle')]//a/@data-jk)", $jobCard));

            if ($title === '' || $company === '' || $jk === '') {
                continue;
            }

            // normalize spaces
            $title = preg_replace('/\s+/', ' ', $title);
            $company = preg_replace('/\s+/', ' ', $company);
            $location = preg_replace('/\s+/', ' ', $location);

            // avoid duplicates
            if (isset($seen[$jk])) {
                continue;
            }
            $seen[$jk] = true;

            $jobs[] = [
                'job_title'    => $title,
                'job_link'     => $this->buildIndeedJobLink($relativeLink),
                'company_name' => $company,
                'location'     => $location,
                'time'         => null,
                'image_link'   => null,
                'platform'     => 'Indeed',
            ];
        }

        libxml_clear_errors();

        return $jobs;
    }

    private function buildIndeedJobLink(string $relativeOrAbsoluteUrl): string
    {
        // If it's already a viewjob link, keep it
        if (str_contains($relativeOrAbsoluteUrl, '/viewjob?')) {
            return str_starts_with($relativeOrAbsoluteUrl, 'http')
                ? $relativeOrAbsoluteUrl
                : 'https://www.indeed.com' . $relativeOrAbsoluteUrl;
        }

        // If it's a /rc/clk link, extract jk and build viewjob URL
        if (str_contains($relativeOrAbsoluteUrl, '/rc/clk?')) {
            $full = str_starts_with($relativeOrAbsoluteUrl, 'http')
                ? $relativeOrAbsoluteUrl
                : 'https://www.indeed.com' . $relativeOrAbsoluteUrl;

            $parts = parse_url($full);
            $query = [];
            parse_str($parts['query'] ?? '', $query);

            if (!empty($query['jk'])) {
                return 'https://www.indeed.com/viewjob?jk=' . urlencode($query['jk']);
            }

            // fallback if jk missing
            return $full;
        }

        // Default: make absolute
        return str_starts_with($relativeOrAbsoluteUrl, 'http')
            ? $relativeOrAbsoluteUrl
            : 'https://www.indeed.com' . $relativeOrAbsoluteUrl;
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
                'timeout'          => 160.0,         // 160 seconds for JS rendering
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
                'timeout'          => 160.0,
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
     * Fetch and extract job description from a job posting page
     */
    public function fetchJobDescription($jobUrl, $platform)
    {
        try {
            if (strtolower($platform) === 'indeed') {
                return 'No description available.';
            } elseif (strtolower($platform) === 'linkedin') {
                return $this->fetchLinkedInJobDescription($jobUrl);
            } elseif (strtolower($platform) === 'wellfound') {
                return $this->fetchWellfoundJobDescription($jobUrl);
            } elseif (strtolower($platform) === 'workday') {
                return $this->fetchWorkdayJobDescription($jobUrl);
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch description from $platform: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch job description from Indeed job posting
     */
    private function fetchIndeedJobDescription($jobUrl)
    {
        try {
            // Use ZenRows to bypass anti-scraping (with 5-second timeout)
            $response = Http::timeout(5)->get($this->zenRowsApiUrl, [
                'apikey' => $this->zenrowApiKey,
                'url' => $jobUrl,
                'mode' => 'auto',
            ]);

            if (!$response->successful()) {
                return null;
            }

            return $this->extractIndeedJobDescription($response->body());
        } catch (\Exception $e) {
            Log::warning("Error fetching Indeed job description: " . $e->getMessage());
            return null;
        }
    }

    private function extractIndeedJobDescription($html)
    {
        try {
            // 1) Grab all JSON-LD blocks
            if (!preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
                return null;
            }

            foreach ($matches[1] as $jsonText) {
                $jsonText = trim($jsonText);
                if ($jsonText === '') continue;

                $data = json_decode($jsonText, true);
                if (json_last_error() !== JSON_ERROR_NONE) continue;

                // JSON-LD can be an object or an array
                $items = (is_array($data) && array_is_list($data)) ? $data : [$data];

                foreach ($items as $item) {
                    if (!is_array($item)) continue;

                    // Some pages use @graph
                    if (isset($item['@graph']) && is_array($item['@graph'])) {
                        $items = array_merge($items, $item['@graph']);
                        continue;
                    }

                    if (($item['@type'] ?? null) !== 'JobPosting') continue;

                    $desc = $item['description'] ?? null;
                    if (!$desc) continue;

                    // 2) Decode + clean
                    $desc = html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // Keep basic formatting
                    $desc = preg_replace('/<br\s*\/?>/i', "\n", $desc);
                    $desc = preg_replace('/<\/(p|div|li|h\d)>/i', "\n", $desc);

                    $desc = strip_tags($desc);

                    // Normalize whitespace
                    $desc = str_replace(["\r\n", "\r"], "\n", $desc);
                    $desc = preg_replace("/[ \t]+/", " ", $desc);
                    $desc = preg_replace("/\n{3,}/", "\n\n", $desc);

                    $desc = trim($desc);

                    return $desc ?: null;
                }
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Error extracting Indeed description: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch job description from LinkedIn job posting
     */
    private function fetchLinkedInJobDescription($jobUrl)
    {
        try {
            // LinkedIn requires specific headers and handling (with 4-second timeout)
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->timeout(4)->get($jobUrl);
            
            if (!$response->successful()) {
                return null;
            }

            return $this->extractLinkedInJobDescription($response->body());
        } catch (\Exception $e) {
            Log::warning("Error fetching LinkedIn job description: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract description from LinkedIn HTML
     */
    private function extractLinkedInJobDescription($html)
    {
        try {
            // 1) Get JSON-LD blocks
            if (!preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
                return null;
            }

            foreach ($matches[1] as $jsonText) {
                $jsonText = trim($jsonText);
                if ($jsonText === '') continue;

                $data = json_decode($jsonText, true);
                if (json_last_error() !== JSON_ERROR_NONE) continue;

                // Sometimes it's an array of objects
                $items = is_array($data) && array_is_list($data) ? $data : [$data];

                foreach ($items as $item) {
                    if (!is_array($item)) continue;

                    // Look for JobPosting
                    $type = $item['@type'] ?? null;
                    if ($type !== 'JobPosting') continue;

                    $desc = $item['description'] ?? null;
                    if (!$desc) continue;

                    // 2) Decode HTML entities (&lt;br&gt; etc) then remove tags
                    $desc = html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $desc = str_replace(["\r\n", "\r"], "\n", $desc);

                    // Convert <br> and </li> into newlines before stripping tags
                    $desc = preg_replace('/<br\s*\/?>/i', "\n", $desc);
                    $desc = preg_replace('/<\/li>/i', "\n", $desc);
                    $desc = strip_tags($desc);

                    // Normalize whitespace
                    $desc = preg_replace("/\n{3,}/", "\n\n", $desc);
                    $desc = trim($desc);

                    return $desc ?: null;
                }
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Error extracting LinkedIn description: " . $e->getMessage());
            return null;
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
        return 'https://workday.wd5.myworkdayjobs.com/Workday?q=' . $roleEncoded. '&Location_Country=' . urlencode($country);
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

    /**
     * Fetch job description from Wellfound job posting
     */
    private function fetchWellfoundJobDescription($jobUrl)
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ])->timeout(4)->get($jobUrl);
            
            if (!$response->successful()) {
                return null;
            }
            
            return $this->extractWellfoundJobDescription($response->body());
        } catch (\Exception $e) {
            Log::warning("Error fetching Wellfound job description: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract description from Wellfound HTML
     */
    private function extractWellfoundJobDescription($html)
    {
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            $xpath = new \DOMXPath($dom);
            
            // Look for job description in common Wellfound selectors
            $desc = trim($xpath->evaluate("string(.//div[@class='description'] | .//div[@class='job-description'] | .//section[@class='job-details'])"));
            
            if (!empty($desc)) {
                $desc = html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $desc = preg_replace('/<br\s*\/?>/i', "\n", $desc);
                $desc = preg_replace('/<\/(p|div|li|h\d)>/i', "\n", $desc);
                $desc = strip_tags($desc);
                $desc = preg_replace("/\n{3,}/", "\n\n", $desc);
                $desc = trim($desc);
                
                return $desc ?: null;
            }
            
            return null;
        } catch (\Throwable $e) {
            Log::error("Error extracting Wellfound description: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch job description from WorkDay job posting
     */
    private function fetchWorkdayJobDescription($jobUrl)
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ])->timeout(4)->get($jobUrl);
            
            if (!$response->successful()) {
                return null;
            }
            
            return $this->extractWorkdayJobDescription($response->body());
        } catch (\Exception $e) {
            Log::warning("Error fetching WorkDay job description: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract description from WorkDay HTML
     */
    private function extractWorkdayJobDescription($html)
    {
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            $xpath = new \DOMXPath($dom);
            
            // Look for job description in common WorkDay selectors
            $desc = trim($xpath->evaluate("string(.//div[@class='job-description'] | .//div[@class='description'] | .//section[@data-cv-section='description'])"));
            
            if (!empty($desc)) {
                $desc = html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $desc = preg_replace('/<br\s*\/?>/i', "\n", $desc);
                $desc = preg_replace('/<\/(p|div|li|h\d)>/i', "\n", $desc);
                $desc = strip_tags($desc);
                $desc = preg_replace("/\n{3,}/", "\n\n", $desc);
                $desc = trim($desc);
                
                return $desc ?: null;
            }
            
            return null;
        } catch (\Throwable $e) {
            Log::error("Error extracting WorkDay description: " . $e->getMessage());
            return null;
        }
    }
}