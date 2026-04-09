<?php

namespace App\Services\Placement;

use App\Models\PlacementProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ResumeParserService
{
    protected $openaiApiKey;
    protected $openaiModel = 'gpt-4-turbo';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Parse uploaded resume file
     */
    public function parseResume($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return match($extension) {
            'pdf' => $this->parsePdf($filePath),
            'docx', 'doc' => $this->parseDocx($filePath),
            'txt' => $this->parseTxt($filePath),
            default => null,
        };
    }

    /**
     * Parse PDF resume
     */
    private function parsePdf($filePath)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path('app/private/' . $filePath));
            $text = $pdf->getText();

            return $this->extractDataWithChatGPT($text);
        } catch (\Exception $e) {
            Log::error('PDF parsing failed: ' . $e->getMessage());
            return null;
        }
    }

    
    private function parseDocx($filePath)
    {
        try {
            $phpWord = IOFactory::load(storage_path('app/private/' . $filePath));
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . ' ';
                    }
                }
            }

            return $this->extractDataWithChatGPT($text);
        } catch (\Exception $e) {
            Log::error('DOCX parsing failed: ' . $e->getMessage());
            return null;
        }
    }

    
    private function parseTxt($filePath)
    {
        try {
            $text = Storage::get($filePath);
            return $this->extractDataWithChatGPT($text);
        } catch (\Exception $e) {
            Log::error('TXT parsing failed: ' . $e->getMessage());
            return null;
        }
    }

    private function extractDataWithChatGPT($text)
    {
        try {
            $prompt = $this->buildExtractionPrompt($text);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->openaiModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert resume parser. Extract information from resumes and return structured JSON data.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.3,
                'max_tokens' => 2000,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API error: ' . $response->body());
                // Fallback to regex-based extraction
                return $this->extractDataFromText($text);
            }

            $result = $response->json();
            if (!isset($result['choices'][0]['message']['content'])) {
                Log::error('Unexpected OpenAI response structure');
                return $this->extractDataFromText($text);
            }

            $jsonContent = $result['choices'][0]['message']['content'];
            // Extract JSON from the response (in case there's extra text)
            preg_match('/\{[\s\S]*\}/', $jsonContent, $matches);
            
            if (empty($matches)) {
                Log::warning('No JSON found in OpenAI response');
                return $this->extractDataFromText($text);
            }

            $data = json_decode($matches[0], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('JSON decode error: ' . json_last_error_msg());
                return $this->extractDataFromText($text);
            }

            // Ensure all required fields exist
            $data = array_merge([
                'skills' => [],
                'years_experience' => null,
                'companies' => [],
                'education' => [],
                'job_titles' => [],
                'sectors' => [],
                'seniority_level' => 'entry',
                'raw_text' => substr($text, 0, 5000), // Store first 5000 chars
            ], $data);
            return $data;

        } catch (\Exception $e) {

            Log::error('ChatGPT extraction failed: ' . $e->getMessage());
            // Fallback to regex-based extraction
            return $this->extractDataFromText($text);
        }
    }

  
    private function buildExtractionPrompt($text)
    {
        return <<<PROMPT
        Please analyze the following resume and extract the information in the exact JSON format below. Return ONLY valid JSON without any additional text or markdown formatting.

        Resume:
        ---
        {$text}
        ---

        Return a JSON object with this exact structure:
        {
        "skills": ["skill1", "skill2", ...],
        "years_experience": <number or null>,
        "companies": ["company1", "company2", ...],
        "education": ["degree1", "degree2", ...],
        "job_titles": ["title1", "title2", ...],
        "sectors": ["sector1", "sector2", ...],
        "seniority_level": "entry|mid|senior|executive",
        "suggested_roles": ["role1", "role2", ...]
        }

        Rules:
        - skills: Array of technical and soft skills found in the resume
        - years_experience: Total years of work experience (number only, null if not found)
        - companies: List of companies/organizations where the person worked
        - education: List of degrees or educational qualifications
        - job_titles: List of job titles or positions held
        - sectors: List of industries or sectors (healthcare, finance, technology, etc.)
        - seniority_level: Career level based on experience and titles (entry, mid, senior, or executive)
        - suggested_roles: Up to 4 job roles that fit the candidate's profile based on the skills, experience, and industry

        Return ONLY the JSON object, nothing else and if the uploaded file is not a valid resume, return an empty JSON object.
        PROMPT;
    }

   
    private function extractDataFromText($text)
    {
        $text = strtolower($text);

        $data = [
            'skills' => $this->extractSkills($text),
            'years_experience' => $this->estimateYearsExperience($text),
            'companies' => $this->extractCompanies($text),
            'education' => $this->extractEducation($text),
            'job_titles' => $this->extractJobTitles($text),
            'sectors' => $this->inferSectors($text),
            'seniority_level' => $this->estimateSeniority($text),
            'raw_text' => substr($text, 0, 5000),
        ];

        return $data;
    }

    /**
     * Extract skills from resume text (Fallback)
     */
    private function extractSkills($text)
    {
        $skillsKeywords = [
            // Technical Skills
            'javascript', 'python', 'java', 'c++', 'php', 'ruby', 'go', 'rust', 'kotlin',
            'sql', 'mysql', 'postgresql', 'mongodb', 'firebase', 'aws', 'azure', 'gcp',
            'react', 'vue', 'angular', 'nodejs', 'express', 'django', 'flask', 'laravel',
            'html', 'css', 'sass', 'webpack', 'git', 'docker', 'kubernetes', 'ci/cd',
            'rest api', 'graphql', 'microservices', 'testing', 'junit', 'pytest',
            
            // Business Skills
            'excel', 'powerpoint', 'word', 'salesforce', 'sap', 'erp', 'crm',
            'project management', 'agile', 'scrum', 'kanban', 'lean',
            'data analysis', 'tableau', 'power bi', 'analytics', 'statistics',
            'communication', 'leadership', 'teamwork', 'negotiation',
            'marketing', 'sales', 'customer service', 'content writing',
            'accounting', 'finance', 'budgeting', 'forecasting',
            
            // Soft Skills
            'problem solving', 'critical thinking', 'creativity', 'time management',
            'attention to detail', 'multitasking', 'collaboration', 'adaptability',
        ];

        $foundSkills = [];
        foreach ($skillsKeywords as $skill) {
            if (str_contains($text, $skill)) {
                $foundSkills[] = $skill;
            }
        }

        return array_unique($foundSkills);
    }

    /**
     * Estimate years of experience (Fallback)
     */
    private function estimateYearsExperience($text)
    {
        // Look for year ranges
        if (preg_match_all('/(\d{4})\s*[-–]\s*(\d{4}|present|current)/', $text, $matches)) {
            $years = [];
            foreach ($matches[1] as $index => $startYear) {
                $endYear = strtolower($matches[2][$index]) === 'present' || strtolower($matches[2][$index]) === 'current' 
                    ? date('Y') 
                    : $matches[2][$index];
                $years[] = $endYear - $startYear;
            }
            return intval(array_sum($years) / max(count($years), 1));
        }

        // Look for experience mentions
        if (preg_match('/(\d+)\s*(?:years?|yrs?)\s*(?:of\s+)?(?:experience|exp)/i', $text, $matches)) {
            return intval($matches[1]);
        }

        return null;
    }

    /**
     * Extract companies from resume (Fallback)
     */
    private function extractCompanies($text)
    {
        $companies = [];
        // Look for patterns like "Company Name" followed by position/dates
        if (preg_match_all('/(?:at|@|company|employer)[\s:]*([A-Z][A-Za-z0-9\s&.,-]+)/i', $text, $matches)) {
            $companies = array_slice(array_unique($matches[1]), 0, 10);
        }
        return $companies;
    }

    /**
     * Extract education (Fallback)
     */
    private function extractEducation($text)
    {
        $degrees = [];
        $degreePatterns = ['bachelor', 'master', 'phd', 'diploma', 'associate', 'certificate'];
        
        foreach ($degreePatterns as $degree) {
            if (str_contains($text, $degree)) {
                $degrees[] = $degree;
            }
        }

        return $degrees;
    }

    /**
     * Extract job titles from resume (Fallback)
     */
    private function extractJobTitles($text)
    {
        $jobTitles = [];
        $titlePatterns = [
            'engineer', 'developer', 'analyst', 'manager', 'coordinator', 'specialist',
            'associate', 'senior', 'lead', 'architect', 'director', 'executive',
            'officer', 'administrator', 'support', 'consultant', 'designer',
        ];

        foreach ($titlePatterns as $title) {
            if (str_contains($text, $title)) {
                $jobTitles[] = $title;
            }
        }

        return array_unique($jobTitles);
    }

    /**
     * Infer industry sectors (Fallback)
     */
    private function inferSectors($text)
    {
        $sectors = [];
        $sectorKeywords = [
            'healthcare' => ['hospital', 'medical', 'doctor', 'nurse', 'healthcare', 'pharma'],
            'finance' => ['bank', 'financial', 'investment', 'accounting', 'finance', 'trading'],
            'technology' => ['software', 'tech', 'developer', 'engineer', 'it', 'data'],
            'retail' => ['retail', 'sales', 'store', 'customer', 'ecommerce'],
            'manufacturing' => ['manufacturing', 'production', 'factory', 'supply chain'],
            'education' => ['school', 'university', 'education', 'teacher', 'trainer'],
            'government' => ['government', 'federal', 'state', 'public', 'agency'],
            'nonprofits' => ['nonprofit', 'charity', 'ngo', 'mission'],
        ];

        foreach ($sectorKeywords as $sector => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $sectors[] = $sector;
                    break;
                }
            }
        }

        return array_unique($sectors);
    }

    /**
     * Estimate seniority level (Fallback)
     */
    private function estimateSeniority($text)
    {
        $seniorityKeywords = [
            'executive' => ['ceo', 'cfo', 'cto', 'director', 'vp', 'vice president'],
            'senior' => ['senior', 'principal', 'staff', 'lead', 'head of'],
            'mid' => ['mid', 'intermediate', 'specialist', 'manager'],
            'entry' => ['junior', 'entry', 'assistant', 'trainee', 'associate'],
        ];

        foreach ($seniorityKeywords as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $level;
                }
            }
        }

        // Fallback based on years of experience
        $yearsMatch = preg_match('/(\d+)\s*(?:years?|yrs?)/', $text, $matches);
        if ($yearsMatch) {
            $years = (int)$matches[1];
            if ($years >= 10) return 'senior';
            if ($years >= 5) return 'mid';
            if ($years >= 2) return 'entry';
        }

        return 'entry';
    }

    /**
     * Validate resume for virus scanning (basic check)
     */
    public function validateResume(UploadedFile $file)
    {
        $allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];

        // ✅ Correct extension
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Invalid file format. Allowed: PDF, DOC, DOCX, TXT',
            ];
        }

        // ✅ Correct size check
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            return [
                'valid' => false,
                'message' => 'File size exceeds 5MB limit',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Resume is valid',
        ];
    }
}
