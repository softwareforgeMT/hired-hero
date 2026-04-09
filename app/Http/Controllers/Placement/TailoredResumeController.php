<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\JobMatch;
use App\Models\PlacementProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class TailoredResumeController extends Controller
{
    protected $zenrowApiKey;
    protected $zenRowsApiUrl;

    public function __construct()
    {
        $this->middleware('auth');
        $this->zenrowApiKey = env('ZENROWS_PROXY_USER');
        $this->zenRowsApiUrl = env('ZENROWS_API_URL');
    }

    /**
     * Generate tailored resume for a job
     */
    public function generate()
    {
        try {
            $user = Auth::user();

            // Check if user has active subscription
            $activeSubscription = $user->getActiveSubscription();
            if (!$activeSubscription || !$activeSubscription->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need an active subscription to generate tailored resumes',
                    'subscribe_url' => route('front.pricing')
                ], 403);
            }

            $jobId = request('job_id');
            $template = request('template', 'classic');

            $job = JobMatch::find($jobId);
            if (!$job || $job->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job not found'
                ], 404);
            }

            // Get user's placement profile
            $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'User profile not found'
                ], 404);
            }

            if($job->source === 'indeed') {
                $jobDescription = $this->fetchIndeedJobDescription($job->job_url);
                if ($jobDescription) {
                    $job->job_description = $jobDescription;
                    $job->save();
                }
            }
            // Prepare user resume data - handle all JSON encoding safely
            $skills = $this->ensureArray($profile->skills ?? []);
            $resumeDataFromProfile = $this->ensureArray($profile->resume_data ?? []);
            $suggestedRoles = $this->ensureArray($profile->suggested_roles ?? []);
            
            // Ensure nested arrays are also decoded
            if (!empty($resumeDataFromProfile['work_experience'])) {
                $resumeDataFromProfile['work_experience'] = $this->ensureArray($resumeDataFromProfile['work_experience']);
            }
            if (!empty($resumeDataFromProfile['education'])) {
                $resumeDataFromProfile['education'] = $this->ensureArray($resumeDataFromProfile['education']);
            }
            if (!empty($resumeDataFromProfile['languages'])) {
                $resumeDataFromProfile['languages'] = $this->ensureArray($resumeDataFromProfile['languages']);
            }
            if (!empty($resumeDataFromProfile['certifications'])) {
                $resumeDataFromProfile['certifications'] = $this->ensureArray($resumeDataFromProfile['certifications']);
            }
            
            $resumeData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not provided',
                'title' => $suggestedRoles[0] ?? 'Professional',
                'skills' => is_array($skills) ? $skills : [],
                'experience' => $resumeDataFromProfile['work_experience'] ?? [],
                'education' => $resumeDataFromProfile['education'] ?? [],
                'languages' => $resumeDataFromProfile['languages'] ?? [],
                'certifications' => $resumeDataFromProfile['certifications'] ?? [],
                'years_experience' => $profile->years_experience ?? 0,
                'summary' => $resumeDataFromProfile['professional_summary'] ?? 'Dedicated professional with diverse technical skills and proven track record.',
            ];

            // Generate ATS-friendly tailored resume with AI
            $tailoredResume = $this->generateTailoredResumeWithAI($resumeData, $job);

            // Store tailored resume in session for editing
            session(['resume_preview_' . $jobId => [
                'resume_data' => $tailoredResume,
                'template' => $template,
                'job_id' => $jobId
            ]]);

            Log::info('Tailored resume generated', [
                'user_id' => $user->id,
                'job_id' => $jobId,
                'template' => $template
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resume generated successfully. Redirecting to editor...',
                'edit_url' => route('placement.resume.edit', ['jobId' => $jobId, 'template' => $template])
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating tailored resume: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating resume. Please try again.'
            ], 500);
        }
    }


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
     * Generate ATS-friendly resume
     */
    private function generateATSFriendlyResume($userResume, $job, $template)
    {
        // Use AI to tailor resume content based on job requirements
        $tailoredResume = $this->generateTailoredResumeWithAI($userResume, $job);
        
        // Build tailored resume content with AI-generated data
        $html = $this->buildResumeHTML($tailoredResume, $job, [], $template);

        return $html;
    }

    /**
     * Generate tailored resume content using AI
     */
    private function generateTailoredResumeWithAI($userResume, $job)
    {
        $jobDescription = $job->job_description ?? '';
        $jobTitle = $job->job_title ?? '';
        $companyName = $job->company_name ?? 'Company';

        // Prepare user profile data for AI
        $userProfileText = $this->formatUserProfileForAI($userResume);

        // Create AI prompt for tailored resume generation
        $prompt = $this->createTailoredResumePrompt($userProfileText, $jobDescription, $jobTitle, $companyName);

        // Call AI service to generate tailored resume
        try {
            $aiResponse = $this->callAIService($prompt);
            $tailoredData = $this->parseTailoredResumeResponse($aiResponse, $userResume);
            return $tailoredData;
        } catch (\Exception $e) {
            \Log::warning('AI tailored resume generation failed, using original data: ' . $e->getMessage());
            return $userResume; // Fallback to original data if AI fails
        }
    }

    /**
     * Format user profile data for AI processing
     */
    private function formatUserProfileForAI($userResume)
    {
        $text = "User Name: " . ($userResume['name'] ?? 'N/A') . "\n";
        $text .= "Professional Title: " . ($userResume['title'] ?? 'N/A') . "\n";
        $text .= "Email: " . ($userResume['email'] ?? 'N/A') . "\n";
        $text .= "Phone: " . ($userResume['phone'] ?? 'N/A') . "\n";
        $text .= "Years Experience: " . ($userResume['years_experience'] ?? 0) . "\n\n";
        
        $text .= "Professional Summary:\n" . ($userResume['summary'] ?? 'No summary provided') . "\n\n";

        if (!empty($userResume['skills'])) {
            $text .= "Skills: " . implode(', ', $userResume['skills']) . "\n\n";
        }

        if (!empty($userResume['experience'])) {
            $text .= "Work Experience:\n";
            foreach ($userResume['experience'] as $exp) {
                $text .= "- " . ($exp['job_title'] ?? 'N/A') . " at " . ($exp['company'] ?? 'N/A') . "\n";
                $text .= "  " . ($exp['description'] ?? '') . "\n";
            }
            $text .= "\n";
        }

        if (!empty($userResume['education'])) {
            $text .= "Education:\n";
            foreach ($userResume['education'] as $edu) {
                $text .= "- " . ($edu['degree'] ?? 'N/A') . " in " . ($edu['field'] ?? 'N/A') . "\n";
                $text .= "  " . ($edu['institution'] ?? '') . "\n";
            }
            $text .= "\n";
        }

        return $text;
    }

    /**
     * Create prompt for AI to generate tailored resume
     */
    private function createTailoredResumePrompt($userProfile, $jobDescription, $jobTitle, $companyName)
    {
        return <<<PROMPT
You are an expert resume writer. Based on the following user profile and job description, generate a tailored resume for this specific position.

USER PROFILE:
$userProfile

JOB DESCRIPTION:
$jobDescription

TASK:
Create a tailored resume that matches the job requirements for the position of "$jobTitle" at "$companyName". 

For each section, tailor the content to highlight relevant skills and experiences that match the job requirements:

1. **Professional Summary**: Write 2-3 sentences that highlight the user's experience in relation to this specific job.

2. **Work Experience**: Select and rewrite the most relevant work experiences (max 3), emphasizing skills and achievements that match the job requirements.

3. **Education**: List relevant education and certifications.

4. **Skills**: Extract and prioritize skills that match the job description (max 10 most relevant skills).

Respond in the following JSON format ONLY, no other text:
{
  "summary": "Tailored professional summary here",
  "experience": [
    {
      "job_title": "Job Title",
      "company": "Company Name",
      "location": "Location",
      "description": "Tailored description emphasizing relevant achievements",
      "start_date": "2020-01-01",
      "end_date": "2023-12-31",
      "currently_working": false
    }
  ],
  "education": [
    {
      "degree": "Degree Name",
      "field": "Field of Study",
      "institution": "University/School Name",
      "graduation_date": "2020-06-01"
    }
  ],
  "skills": ["Skill1", "Skill2", "Skill3"],
  "languages": ["Language1", "Language2"],
  "certifications": [
    {
      "name": "Certification Name",
      "issuer": "Issuer Name",
      "issue_date": "2023-01-15"
    }
  ]
}

IMPORTANT: Return ONLY valid JSON, nothing else.
PROMPT;
    }

    /**
     * Call AI service to generate content
     */
    private function callAIService($prompt)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert resume writer. Always respond with valid JSON only.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $body = $response->json();
        
        if (isset($body['choices'][0]['message']['content'])) {
            return $body['choices'][0]['message']['content'];
        }

        throw new \Exception('Invalid response from OpenAI API');
    }

    /**
     * Parse AI response and merge with user resume data
     */
    private function parseTailoredResumeResponse($aiResponse, $userResume)
    {
        try {
            // Extract JSON from response
            $jsonMatch = null;
            if (preg_match('/\{[\s\S]*\}/', $aiResponse, $matches)) {
                $jsonMatch = $matches[0];
            } else {
                $jsonMatch = $aiResponse;
            }

            $tailoredData = json_decode($jsonMatch, true);
            
            if (!$tailoredData) {
                throw new \Exception('Failed to parse AI response');
            }

            // Ensure experience has all required fields
            $experience = [];
            if (!empty($tailoredData['experience'])) {
                foreach ($tailoredData['experience'] as $exp) {
                    $experience[] = [
                        'job_title' => $exp['job_title'] ?? 'N/A',
                        'company' => $exp['company'] ?? 'N/A',
                        'location' => $exp['location'] ?? '',
                        'description' => $exp['description'] ?? '',
                        'start_date' => $exp['start_date'] ?? null,
                        'end_date' => $exp['end_date'] ?? null,
                        'currently_working' => $exp['currently_working'] ?? false,
                    ];
                }
            }

            // Ensure education has all required fields
            $education = [];
            if (!empty($tailoredData['education'])) {
                foreach ($tailoredData['education'] as $edu) {
                    $education[] = [
                        'degree' => $edu['degree'] ?? 'N/A',
                        'field' => $edu['field'] ?? '',
                        'institution' => $edu['institution'] ?? '',
                        'graduation_date' => $edu['graduation_date'] ?? null,
                        'description' => $edu['description'] ?? '',
                    ];
                }
            }

            // Ensure certifications have all required fields
            $certifications = [];
            if (!empty($tailoredData['certifications'])) {
                foreach ($tailoredData['certifications'] as $cert) {
                    $certifications[] = [
                        'name' => $cert['name'] ?? 'N/A',
                        'issuer' => $cert['issuer'] ?? '',
                        'issue_date' => $cert['issue_date'] ?? null,
                    ];
                }
            }

            // Merge AI-generated data with original user data
            return [
                'name' => $userResume['name'],
                'email' => $userResume['email'],
                'phone' => $userResume['phone'],
                'title' => $userResume['title'],
                'location' => $userResume['location'] ?? '',
                'summary' => $tailoredData['summary'] ?? $userResume['summary'],
                'skills' => is_array($tailoredData['skills'] ?? null) ? $tailoredData['skills'] : ($userResume['skills'] ?? []),
                'experience' => $experience ?: ($userResume['experience'] ?? []),
                'education' => $education ?: ($userResume['education'] ?? []),
                'languages' => is_array($tailoredData['languages'] ?? null) ? $tailoredData['languages'] : ($userResume['languages'] ?? []),
                'certifications' => $certifications ?: ($userResume['certifications'] ?? []),
                'years_experience' => $userResume['years_experience'] ?? 0,
            ];
        } catch (\Exception $e) {
            \Log::warning('Failed to parse AI response: ' . $e->getMessage());
            return $userResume; // Return original if parsing fails
        }
    }

    /**
     * Extract key skills from job description and match with user skills
     */
    private function extractKeySkills($jobDescription, $userSkills = [])
    {
        $skills = [];
        
        // Common tech skills to look for
        $commonSkills = [
            'PHP', 'Laravel', 'Python', 'JavaScript', 'React', 'Vue', 'Node.js',
            'SQL', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'HTML', 'CSS', 'REST API', 'GraphQL', 'AWS', 'Docker', 'Git',
            'Java', 'C++', 'Ruby', 'Go', 'Rust', 'TypeScript',
            'Agile', 'Scrum', 'JIRA', 'DevOps', 'CI/CD', 'Testing',
            'Leadership', 'Communication', 'Problem-solving', 'Project Management'
        ];

        // Find matching skills in job description
        foreach ($commonSkills as $skill) {
            if (stripos($jobDescription, $skill) !== false) {
                $skills[] = $skill;
            }
        }

        // Add user's skills that match job description keywords
        $jobKeywords = str_word_count(strtolower($jobDescription), 1);
        foreach ($userSkills as $userSkill) {
            if (stripos($jobDescription, $userSkill) !== false && !in_array($userSkill, $skills)) {
                $skills[] = $userSkill;
            }
        }

        return array_unique($skills);
    }

    /**
     * Build resume HTML with selected template
     */
    private function buildResumeHTML($userResume, $job, $keySkills, $template)
    {
        // Prepare data for blade templates
        $personalInfo = [
            'full_name' => $userResume['name'] ?? 'Your Name',
            'professional_title' => $userResume['title'] ?? 'Professional',
            'email' => $userResume['email'] ?? '',
            'phone' => $userResume['phone'] ?? '',
            'location' => $userResume['location'] ?? '',
            'summary' => $userResume['summary'] ?? ''
        ];

        // Ensure all are arrays
        $keySkills = is_array($keySkills) ? $keySkills : [];
        $userSkills = isset($userResume['skills']) && is_array($userResume['skills']) ? $userResume['skills'] : [];
        $allSkills = array_merge($keySkills, $userSkills);
        $allSkills = array_unique(array_filter($allSkills));

        $workExperience = isset($userResume['experience']) && is_array($userResume['experience']) ? $userResume['experience'] : [];
        $education = isset($userResume['education']) && is_array($userResume['education']) ? $userResume['education'] : [];
        $languages = isset($userResume['languages']) && is_array($userResume['languages']) ? $userResume['languages'] : [];
        $certifications = isset($userResume['certifications']) && is_array($userResume['certifications']) ? $userResume['certifications'] : [];

        // Render blade template
        $viewPath = 'placement.resume-templates.' . $template;
        
        if (!view()->exists($viewPath)) {
            // Fallback if template doesn't exist
            return view('placement.resume-templates.classic', [
                'personal_info' => $personalInfo,
                'work_experience' => $workExperience,
                'education' => $education,
                'skills' => $allSkills,
                'languages' => $languages,
                'certifications' => $certifications
            ])->render();
        }

        return view($viewPath, [
            'personal_info' => $personalInfo,
            'work_experience' => $workExperience,
            'education' => $education,
            'skills' => $allSkills,
            'languages' => $languages,
            'certifications' => $certifications
        ])->render();
    }

    /**
     * Preview generated resume with edit capability
     */
    public function preview()
    {
        try {
            $user = Auth::user();
            $jobId = request('job_id');
            $template = request('template', 'classic');
            $resumeData = request('resume_data', []);

            // Store resume data in session for editing
            session(['resume_preview_' . $jobId => [
                'resume_data' => $resumeData,
                'template' => $template
            ]]);

            return response()->json([
                'success' => true,
                'edit_url' => route('placement.resume.edit', ['job_id' => $jobId, 'template' => $template])
            ]);

        } catch (\Exception $e) {
            Log::error('Error previewing resume: ' . $e->getMessage());
            return response()->json(['error' => 'Preview failed'], 500);
        }
    }

    /**
     * Edit resume content
     */
    public function edit($jobId)
    {
        try {
            $user = Auth::user();
            $template = request('template', 'classic');
            $resumeData = session('resume_preview_' . $jobId, []);

            if (empty($resumeData)) {
                return redirect()->route('user.dashboard')->with('error', 'Resume data not found');
            }

            return view('placement.resume-edit', [
                'job_id' => $jobId,
                'template' => $template,
                'resume_data' => $resumeData['resume_data'] ?? [],
                'user' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Error editing resume: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading resume editor');
        }
    }

    /**
     * Save edited resume and generate PDF
     */
    public function saveAndDownload()
    {
        try {
            $user = Auth::user();
            
            // Get data from JSON request
            $input = request()->json()->all();
            $jobId = $input['job_id'] ?? null;
            $template = $input['template'] ?? 'classic';
            
            // Extract resume data
            $resumeData = [
                'name' => $input['name'] ?? '',
                'title' => $input['title'] ?? '',
                'email' => $input['email'] ?? '',
                'phone' => $input['phone'] ?? '',
                'location' => $input['location'] ?? '',
                'summary' => $input['summary'] ?? '',
                'skills' => $input['skills'] ?? [],
                'experience' => $input['experience'] ?? [],
                'education' => $input['education'] ?? [],
                'languages' => $input['languages'] ?? [],
                'certifications' => $input['certifications'] ?? []
            ];

            // Ensure all data is properly formatted
            $personalInfo = [
                'full_name' => $resumeData['name'] ?? 'Your Name',
                'professional_title' => $resumeData['title'] ?? 'Professional',
                'email' => $resumeData['email'] ?? '',
                'phone' => $resumeData['phone'] ?? '',
                'location' => $resumeData['location'] ?? '',
                'summary' => $resumeData['summary'] ?? ''
            ];

            $workExperience = is_array($resumeData['experience']) ? $resumeData['experience'] : [];
            $education = is_array($resumeData['education']) ? $resumeData['education'] : [];
            $languages = is_array($resumeData['languages']) ? $resumeData['languages'] : [];
            $certifications = is_array($resumeData['certifications']) ? $resumeData['certifications'] : [];
            $skills = is_array($resumeData['skills']) ? $resumeData['skills'] : [];

            // Render HTML
            $viewPath = 'placement.resume-templates.' . $template;
            if (!view()->exists($viewPath)) {
                $viewPath = 'placement.resume-templates.classic';
            }

            $html = view($viewPath, [
                'personal_info' => $personalInfo,
                'work_experience' => $workExperience,
                'education' => $education,
                'skills' => $skills,
                'languages' => $languages,
                'certifications' => $certifications
            ])->render();

            // Convert to PDF using dompdf
            $pdf = Pdf::loadHTML($html);
            $fileName = 'Tailored-Resume-' . $user->id . '-' . time() . '.pdf';

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('Error saving and downloading resume: ' . $e->getMessage());
            return response()->json(['error' => 'Download failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download generated resume as PDF
     */
    public function download($file)
    {
        try {
            $user = Auth::user();
            $path = storage_path('app/resumes/' . $file);

            if (!file_exists($path)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            // Verify ownership by checking filename contains user_id
            if (strpos($file, 'resume_' . $user->id) !== 0) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->download($path, 'Tailored-Resume.pdf');

        } catch (\Exception $e) {
            Log::error('Error downloading resume: ' . $e->getMessage());
            return response()->json(['error' => 'Download failed'], 500);
        }
    }

    /**
     * Safely convert data to array, handling JSON strings
     */
    private function ensureArray($data)
    {
        if (is_array($data)) {
            return $data;
        }
        
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
}

