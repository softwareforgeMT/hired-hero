<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSubscription;
use App\Models\CoverLetter;
use App\Models\JobMatch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class CoverLetterService
{
    /**
     * Check if user has cover letter feature enabled
     */
    public function hasCoverLetterFeature(UserSubscription $subscription): bool
    {
        if (!$subscription || !$subscription->plan) {
            return false;
        }

        $accessSection = $subscription->plan->access_section;
        
        if (is_array($accessSection) && isset($accessSection['jobMatches']['ai_tailored_cover'])) {
            $feature = $accessSection['jobMatches']['ai_tailored_cover'];
            return $feature === true || $feature === 'unlimited' || is_numeric($feature);
        } elseif (is_string($accessSection)) {
            $decoded = json_decode($accessSection, true);
            if (isset($decoded['jobMatches']['ai_tailored_cover'])) {
                $feature = $decoded['jobMatches']['ai_tailored_cover'];
                return $feature === true || $feature === 'unlimited' || is_numeric($feature);
            }
        }

        return false;
    }

    /**
     * Get cover letter limit for user's subscription
     * Returns: 'unlimited' or numeric value
     */
    public function getCoverLetterLimit(UserSubscription $subscription)
    {
        if (!$subscription || !$subscription->plan) {
            return 0;
        }

        $accessSection = $subscription->plan->access_section;
        
        if (is_array($accessSection) && isset($accessSection['jobMatches']['ai_tailored_cover'])) {
            $feature = $accessSection['jobMatches']['ai_tailored_cover'];
            if ($feature === 'unlimited') {
                return 'unlimited';
            }
            return is_numeric($feature) ? (int) $feature : 0;
        } elseif (is_string($accessSection)) {
            $decoded = json_decode($accessSection, true);
            if (isset($decoded['jobMatches']['ai_tailored_cover'])) {
                $feature = $decoded['jobMatches']['ai_tailored_cover'];
                if ($feature === 'unlimited') {
                    return 'unlimited';
                }
                return is_numeric($feature) ? (int) $feature : 0;
            }
        }

        return 0;
    }

    /**
     * Get number of covers user has used in current billing period
     */
    public function getCoversUsed(User $user): int
    {
        $subscription = $user->getActiveSubscription();
        
        if (!$subscription) {
            return 0;
        }

        $featuresUsed = $subscription->features_used ?? [];
        
        if (is_string($featuresUsed)) {
            $featuresUsed = json_decode($featuresUsed, true) ?? [];
        }

        return isset($featuresUsed['cover_letters_used']) ? (int) $featuresUsed['cover_letters_used'] : 0;
    }

    /**
     * Increment cover letter usage
     */
    public function incrementCoverLetterUsage(User $user): bool
    {
        $subscription = $user->getActiveSubscription();
        
        if (!$subscription) {
            return false;
        }

        $featuresUsed = $subscription->features_used ?? [];
        
        if (is_string($featuresUsed)) {
            $featuresUsed = json_decode($featuresUsed, true) ?? [];
        }

        if (!isset($featuresUsed['cover_letters_used'])) {
            $featuresUsed['cover_letters_used'] = 0;
        }

        $featuresUsed['cover_letters_used']++;
        $featuresUsed['cover_letters_last_used'] = now()->toDateTimeString();

        // Update subscription
        $subscription->update(['features_used' => $featuresUsed]);

        return true;
    }

    /**
     * Generate cover letter content
     * This is where AI API integration would happen
     */
    public function generateCoverLetter(
        string $jobTitle,
        string $companyName,
        ?string $jobDescription,
        array $userInfo,
        ?string $aiPrompt = null,
        string $planSlug = 'free-plan'
    ): ?string
    {
        try {
            // Prepare the cover letter
            $userFullName = $userInfo['name'] ?? 'Your Name';
            $userEmail = $userInfo['email'] ?? 'your.email@example.com';
            $roles = $userInfo['roles'] ?? [];
            $skills = $userInfo['skills'] ?? [];

            // If Pro plan with job description, generate job-specific cover letter
            if ($planSlug !== 'free-plan' && $jobDescription) {
                return $this->generateAITailoredCoverLetter(
                    $jobTitle,
                    $companyName,
                    $jobDescription,
                    $userFullName,
                    $userEmail,
                    $roles,
                    $skills,
                    $aiPrompt
                );
            }

            // Generate generic cover letter based on roles
            return $this->generateGenericCoverLetter(
                $jobTitle,
                $companyName,
                $userFullName,
                $userEmail,
                $roles,
                $skills,
                $aiPrompt
            );

        } catch (\Exception $e) {
            Log::error('Error generating cover letter: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate AI-tailored cover letter based on job description
     */
    private function generateAITailoredCoverLetter(
        string $jobTitle,
        string $companyName,
        string $jobDescription,
        string $userName,
        string $userEmail,
        array $roles,
        array $skills,
        ?string $aiPrompt = null
    ): string
    {
        try {
            $prompt = $this->createTailoredCoverLetterPrompt(
                $userName,
                $userEmail,
                $jobTitle,
                $companyName,
                $jobDescription,
                $roles,
                $skills,
                $aiPrompt
            );

            $aiResponse = $this->callOpenAIService($prompt);
            return $this->parseCoverLetterResponse($aiResponse);
        } catch (Exception $e) {
            Log::warning('AI tailored cover letter generation failed, using template: ' . $e->getMessage());
            // Fallback to template if AI fails
            return $this->generateTemplateCoverLetter(
                $jobTitle,
                $companyName,
                $userName,
                $userEmail,
                $roles,
                $skills
            );
        }
    }

    /**
     * Generate generic cover letter based on roles
     */
    private function generateGenericCoverLetter(
        string $jobTitle,
        string $companyName,
        string $userName,
        string $userEmail,
        array $roles,
        array $skills,
        ?string $aiPrompt = null
    ): string
    {
        try {
            $prompt = $this->createGenericCoverLetterPrompt(
                $userName,
                $userEmail,
                $jobTitle,
                $companyName,
                $roles,
                $skills,
                $aiPrompt
            );

            $aiResponse = $this->callOpenAIService($prompt);
            return $this->parseCoverLetterResponse($aiResponse);
        } catch (Exception $e) {
            Log::warning('AI generic cover letter generation failed, using template: ' . $e->getMessage());
            // Fallback to template if AI fails
            return $this->generateTemplateCoverLetter(
                $jobTitle,
                $companyName,
                $userName,
                $userEmail,
                $roles,
                $skills
            );
        }
    }

    /**
     * Create prompt for AI tailored cover letter generation
     */
    private function createTailoredCoverLetterPrompt(
        string $userName,
        string $userEmail,
        string $jobTitle,
        string $companyName,
        string $jobDescription,
        array $roles,
        array $skills,
        ?string $aiPrompt = null
    ): string
    {
        $rolesText = !empty($roles) ? implode(', ', $roles) : 'Professional';
        $skillsText = !empty($skills) ? implode(', ', $skills) : 'various technical and professional skills';
        $customPrompt = $aiPrompt ? "\n\nAdditional user instructions: $aiPrompt" : '';

        return <<<PROMPT
You are an expert cover letter writer. Generate a professional, personalized cover letter that matches the job requirements exactly.

USER INFORMATION:
- Name: $userName
- Email: $userEmail
- Professional Background: $rolesText
- Key Skills: $skillsText

JOB DETAILS:
- Position: $jobTitle
- Company: $companyName
- Job Description: $jobDescription$customPrompt

REQUIREMENTS:
1. Write a compelling cover letter that directly addresses this specific job opportunity
2. Match keywords and requirements from the job description
3. Highlight relevant skills and experience
4. Keep it professional yet personable
5. Use a formal business letter format with proper date, greeting, body paragraphs, and closing
6. Length: approximately 250-350 words
7. Do NOT include any markdown formatting, just plain text with proper paragraph breaks

Write the cover letter now. Start with the date, then proceed with the hiring manager greeting, body paragraphs, and professional closing (Sincerely, followed by the user's name).
PROMPT;
    }

    /**
     * Create prompt for AI generic cover letter generation
     */
    private function createGenericCoverLetterPrompt(
        string $userName,
        string $userEmail,
        string $jobTitle,
        string $companyName,
        array $roles,
        array $skills,
        ?string $aiPrompt = null
    ): string
    {
        $rolesText = !empty($roles) ? implode(', ', $roles) : 'Professional';
        $skillsText = !empty($skills) ? implode(', ', $skills) : 'various technical and professional skills';
        $customPrompt = $aiPrompt ? "\n\nAdditional user instructions: $aiPrompt" : '';

        return <<<PROMPT
You are an expert cover letter writer. Generate a professional, personalized cover letter for the specified position.

USER INFORMATION:
- Name: $userName
- Email: $userEmail
- Professional Background: $rolesText
- Key Skills: $skillsText

JOB DETAILS:
- Position: $jobTitle
- Company: $companyName$customPrompt

REQUIREMENTS:
1. Write a professional cover letter highlighting the user's relevant experience
2. Focus on the user's background in $rolesText
3. Emphasize key competencies in $skillsText
4. Express genuine interest in the position and company
5. Use a formal business letter format with proper date, greeting, body paragraphs, and closing
6. Length: approximately 250-350 words
7. Do NOT include any markdown formatting, just plain text with proper paragraph breaks

Write the cover letter now. Start with the date, then proceed with the hiring manager greeting, body paragraphs, and professional closing (Sincerely, followed by the user's name).
PROMPT;
    }

    /**
     * Call OpenAI API service (same pattern as TailoredResumeController)
     */
    private function callOpenAIService(string $prompt): string
    {
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            throw new Exception('OpenAI API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => env('OPENAI_MODEL', 'gpt-4-turbo'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert cover letter writer. Write professional, compelling cover letters that match job requirements.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if ($response->failed()) {
            throw new Exception('OpenAI API error: ' . $response->body());
        }

        $body = $response->json();

        if (isset($body['choices'][0]['message']['content'])) {
            return $body['choices'][0]['message']['content'];
        }

        throw new Exception('Invalid response from OpenAI API');
    }

    /**
     * Parse cover letter from AI response
     */
    private function parseCoverLetterResponse(string $aiResponse): string
    {
        // AI response should be plain text cover letter, trim and return
        $coverLetter = trim($aiResponse);

        if (empty($coverLetter)) {
            throw new Exception('Empty response from AI service');
        }

        return $coverLetter;
    }

    /**
     * Generate template-based cover letter (fallback when AI fails)
     */
    private function generateTemplateCoverLetter(
        string $jobTitle,
        string $companyName,
        string $userName,
        string $userEmail,
        array $roles,
        array $skills
    ): string
    {
        $skillsText = !empty($skills) ? implode(', ', $skills) : 'relevant technical and professional skills';
        $rolesText = !empty($roles) ? implode(', ', $roles) : 'professional experience';
        $currentDate = now()->format('F j, Y');

        return <<<EOL
$currentDate

Hiring Manager
$companyName

Dear Hiring Manager,

I am writing to express my strong interest in the $jobTitle position at $companyName. With my background in $rolesText and proven expertise in $skillsText, I am confident in my ability to contribute significantly to your team.

Throughout my career, I have developed a strong foundation in the skills identified in your job posting. Your job description resonates deeply with my professional aspirations, and I am excited about the opportunity to bring my unique skill set and passion to your organization.

My experience aligns well with your requirements:
- Proficiency in $skillsText
- Strong background in $rolesText
- Track record of delivering results and driving value

I am particularly drawn to $companyName because of your commitment to innovation and excellence. I am confident that my technical skills, problem-solving abilities, and collaborative approach make me an ideal candidate for this role.

I would welcome the opportunity to discuss how my background, skills, and enthusiasm can contribute to your team's success. Thank you for considering my application. I look forward to speaking with you soon.

Sincerely,

$userName
$userEmail

EOL;
    }

    /**
     * Save cover letter for download
     */
    public function saveCoverLetterForDownload(
        User $user,
        string $content,
        string $jobTitle,
        string $companyName,
        ?int $jobId = null
    ): ?string
    {
        try {
            // Create storage directory if not exists
            $userDir = "cover-letters/{$user->id}";
            
            if (!Storage::exists($userDir)) {
                Storage::makeDirectory($userDir);
            }

            // Generate filename
            $fileName = 'Cover_Letter_' . Str::slug($jobTitle) . '_' . Str::slug($companyName) . '_' . time() . '.pdf';
            
            // Debug: Log the incoming content
            Log::info('PDF Generation - Incoming content length: ' . strlen($content));
            Log::info('PDF Generation - Content preview: ' . substr($content, 0, 200));
            
            // Create HTML for PDF
            $html = $this->generatePdfHtml($content);
            
            // Debug: Log the generated HTML
            Log::info('PDF Generation - Generated HTML length: ' . strlen($html));
            Log::info('PDF Generation - HTML preview: ' . substr($html, 0, 300));
            
            // Create PDF
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isPhpEnabled', false);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            // Save PDF file
            $pdfContent = $dompdf->output();
            Log::info('PDF Generation - PDF output size: ' . strlen($pdfContent) . ' bytes');
            
            Storage::put("{$userDir}/{$fileName}", $pdfContent);
            
            Log::info('PDF Generation - File saved successfully: ' . $fileName);

            // Save cover letter to database
            $filePath = "{$userDir}/{$fileName}";
            $downloadUrl = route('placement.covers.download', ['file' => $fileName]);

            // Check if job match exists
            $jobMatch = null;
            if ($jobId) {
                $jobMatch = JobMatch::where('id', $jobId)
                    ->where('user_id', $user->id)
                    ->first();
            }

            // Save or update cover letter in database
            $coverLetter = CoverLetter::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'job_title' => $jobTitle,
                    'company_name' => $companyName,
                    'status' => 'finalized',
                ],
                [
                    'job_match_id' => $jobMatch?->id,
                    'content' => $content,
                    'file_path' => $filePath,
                    'file_url' => $downloadUrl,
                    'status' => 'finalized',
                ]
            );

            Log::info('Cover Letter saved to database', [
                'user_id' => $user->id,
                'cover_letter_id' => $coverLetter->id,
                'job_title' => $jobTitle,
                'company_name' => $companyName,
            ]);

            return $downloadUrl;

        } catch (\Exception $e) {
            Log::error('Error saving cover letter: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Generate HTML for PDF
     */
    private function generatePdfHtml(string $content): string
    {
        // Normalize line endings (handle both \r\n and \n)
        $content = str_replace("\r\n", "\n", $content);
        
        // Escape HTML entities but preserve formatting
        $safeContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Convert line breaks into proper HTML paragraphs
        $paragraphs = explode("\n\n", $safeContent);
        $htmlParagraphs = '';
        
        foreach ($paragraphs as $para) {
            if (trim($para)) {
                // Replace single newlines with <br> within paragraphs
                $para = str_replace("\n", "<br>", trim($para));
                $htmlParagraphs .= "<p>{$para}</p>";
            }
        }
        
        // If no paragraphs were created (single block of text), wrap the whole thing
        if (empty($htmlParagraphs)) {
            $safeContent = str_replace("\n", "<br>", $safeContent);
            $htmlParagraphs = "<p>{$safeContent}</p>";
        }
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cover Letter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20mm;
            font-size: 12px;
        }
        p {
            margin-bottom: 15px;
            text-align: justify;
        }
        br {
            display: block;
            margin: 0;
        }
    </style>
</head>
<body>
{$htmlParagraphs}
</body>
</html>
HTML;
    }

    /**
     * Get all cover letters for a user
     */
    public function getUserCoverLetters(User $user, $status = null)
    {
        $query = CoverLetter::where('user_id', $user->id);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get a specific cover letter
     */
    public function getCoverLetter(CoverLetter $coverLetter, User $user)
    {
        if ($coverLetter->user_id !== $user->id) {
            return null;
        }

        return $coverLetter;
    }

    /**
     * Delete/archive a cover letter
     */
    public function deleteCoverLetter(CoverLetter $coverLetter, User $user): bool
    {
        if ($coverLetter->user_id !== $user->id) {
            return false;
        }

        try {
            // Delete file if exists
            if ($coverLetter->file_path && Storage::exists($coverLetter->file_path)) {
                Storage::delete($coverLetter->file_path);
            }

            // Delete from database
            $coverLetter->delete();
            
            Log::info('Cover letter deleted', [
                'user_id' => $user->id,
                'cover_letter_id' => $coverLetter->id,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error deleting cover letter: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get count of finalized cover letters for a user
     */
    public function getFinalizedCoverLettersCount(User $user): int
    {
        return CoverLetter::where('user_id', $user->id)
            ->where('status', 'finalized')
            ->count();
    }

    /**
     * Check if user has exceeded cover letter limit
     */
    public function hasExceededLimit(User $user): bool
    {
        $subscription = $user->getActiveSubscription();
        
        if (!$subscription || !$subscription->isActive()) {
            return true;
        }

        $limit = $this->getCoverLetterLimit($subscription);
        
        if ($limit === 'unlimited') {
            return false;
        }

        $used = $this->getCoversUsed($user);
        return $used >= $limit;
    }

    /**
     * Get cover letter limit and usage summary
     */
    public function getLimitSummary(User $user): array
    {
        $subscription = $user->getActiveSubscription();
        
        if (!$subscription) {
            return [
                'hasFeature' => false,
                'limit' => 0,
                'used' => 0,
                'remaining' => 0,
                'isUnlimited' => false,
            ];
        }

        $limit = $this->getCoverLetterLimit($subscription);
        $used = $this->getCoversUsed($user);
        $isUnlimited = $limit === 'unlimited';

        return [
            'hasFeature' => $this->hasCoverLetterFeature($subscription),
            'limit' => $isUnlimited ? null : $limit,
            'used' => $used,
            'remaining' => $isUnlimited ? null : max(0, $limit - $used),
            'isUnlimited' => $isUnlimited,
        ];
    }
}
