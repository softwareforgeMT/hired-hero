<?php

namespace App\Services\Placement;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIResumeEnhancementService
{
    /**
     * OpenAI API endpoint
     */
    protected string $apiUrl = 'https://api.openai.com/v1/chat/completions';

    /**
     * OpenAI API key
     */
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY');
    }

    /**
     * Enhance professional summary with AI
     */
    public function enhanceSummary(string $summary, string $jobTitle = '', int|string $yearsExperience = 0): string
    {
        if (empty(trim($summary))) {
            return '';
        }

        $prompt = "You are a professional resume writer. Enhance this professional summary to be more compelling, professional, and impactful. " .
                  "Keep it concise (2-3 sentences, max 75 words). " .
                  ($jobTitle ? "The person's job title is: {$jobTitle}. " : "") .
                  ($yearsExperience ? "Years of experience: {$yearsExperience}. " : "") .
                  "Make it ATS-friendly and engaging. Return only the enhanced summary, nothing else.\n\n" .
                  "Original summary: {$summary}";

        return $this->callOpenAI($prompt);
    }

    /**
     * Enhance work experience description
     */
    public function enhanceJobDescription(string $description, string $jobTitle, string $company): string
    {
        if (empty(trim($description))) {
            return '';
        }

        $prompt = "You are a professional resume writer. Enhance this job description to be more professional, achievement-focused, and impactful. " .
                  "Convert it into bullet points or a concise paragraph (2-3 sentences max). " .
                  "Use strong action verbs and quantifiable results where possible. " .
                  "Job Title: {$jobTitle}, Company: {$company}. " .
                  "Make it ATS-friendly and compelling. Return only the enhanced description, nothing else.\n\n" .
                  "Original description: {$description}";

        return $this->callOpenAI($prompt);
    }

    /**
     * Generate professional opening statement based on profile
     */
    public function generateProfessionalOpening(array $profileData): string
    {
        $jobTitle = $profileData['professional_title'] ?? 'Professional';
        $skills = implode(', ', array_slice($profileData['skills'] ?? [], 0, 5));
        $years = $profileData['years_experience'] ?? 0;

        $prompt = "You are a professional resume writer. Generate a compelling professional opening statement (2-3 sentences, max 85 words). " .
                  "Job Title: {$jobTitle}. " .
                  "Key Skills: {$skills}. " .
                  "Years of Experience: {$years}. " .
                  "Make it engaging, professional, and ATS-friendly. Return only the opening statement, nothing else.";

        return $this->callOpenAI($prompt);
    }

    /**
     * Enhance all content in resume data
     */
    public function enhanceResumeData(array $resumeData): array
    {
        try {
            // Enhance professional summary
            if (!empty($resumeData['personal_info']['summary'] ?? null)) {
                $resumeData['personal_info']['summary'] = $this->enhanceSummary(
                    $resumeData['personal_info']['summary'],
                    $resumeData['personal_info']['professional_title'] ?? '',
                    $resumeData['years_experience'] ?? 0
                );
            }

            // Enhance work experience descriptions
            if (!empty($resumeData['work_experience'])) {
                foreach ($resumeData['work_experience'] as &$experience) {
                    if (!empty($experience['description'])) {
                        $experience['description'] = $this->enhanceJobDescription(
                            $experience['description'],
                            $experience['job_title'] ?? '',
                            $experience['company'] ?? ''
                        );
                    }
                }
            }

            // If no summary, try to generate one
            if (empty($resumeData['personal_info']['summary'])) {
                $resumeData['personal_info']['summary'] = $this->generateProfessionalOpening($resumeData);
            }

            return $resumeData;
        } catch (\Exception $e) {
            Log::error('AI content enhancement failed', [
                'error' => $e->getMessage(),
            ]);
            return $resumeData; // Return original data if AI fails
        }
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $prompt): string
    {
        try {
            if (empty($this->apiKey)) {
                Log::warning('OpenAI API key not configured, skipping AI enhancement');
                return '';
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional resume writer specializing in creating compelling, ATS-optimized resume content.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return trim($content ?? '');
            }

            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return '';
        } catch (\Exception $e) {
            Log::error('OpenAI API call failed', [
                'error' => $e->getMessage(),
            ]);
            return '';
        }
    }
}
