<?php

namespace App\Services\Placement;

use App\Models\PlacementProfile;
use App\Models\CareerLane;
use Illuminate\Support\Facades\Http;

class AIRoleMappingService
{
    /**
     * Generate AI-suggested role titles based on resume data
     */
    public function suggestRoles(PlacementProfile $profile)
    {
        $resumeData = $profile->resume_data ?? [];
        $skills = $profile->skills ?? [];
        $jobTitles = $resumeData['job_titles'] ?? [];
        $yearsExperience = $profile->years_experience;
        $sectors = $profile->past_sectors ?? [];

        $suggestedRoles = [];

        // Map skills to career lanes
        $skillBasedRoles = $this->mapSkillsToRoles($skills, $yearsExperience);
        $suggestedRoles = array_merge($suggestedRoles, $skillBasedRoles);

        // Map past titles to career lanes
        $titleBasedRoles = $this->mapTitlesToRoles($jobTitles);
        $suggestedRoles = array_merge($suggestedRoles, $titleBasedRoles);

        // Map sectors to career lanes
        $sectorBasedRoles = $this->mapSectorsToRoles($sectors);
        $suggestedRoles = array_merge($suggestedRoles, $sectorBasedRoles);

        // Remove duplicates and limit to 4
        $uniqueRoles = array_unique($suggestedRoles);
        $suggestedRoles = array_slice($uniqueRoles, 0, 4);

        return $suggestedRoles;
    }

    /**
     * Map skills to potential roles
     */
    private function mapSkillsToRoles($skills, $yearsExperience = null)
    {
        $skillRoleMapping = [
            // Data Skills
            'sql' => ['Data Analyst', 'Database Administrator', 'Data Engineer'],
            'python' => ['Data Scientist', 'Python Developer', 'Data Analyst'],
            'tableau' => ['Business Analyst', 'Data Analyst', 'BI Developer'],
            'power bi' => ['Business Analyst', 'BI Developer', 'Data Analyst'],
            'excel' => ['Business Analyst', 'Financial Analyst', 'Data Analyst'],
            
            // Web Development
            'javascript' => ['Web Developer', 'Frontend Developer', 'Full Stack Developer'],
            'react' => ['Frontend Developer', 'Web Developer', 'React Developer'],
            'nodejs' => ['Backend Developer', 'Full Stack Developer', 'Node.js Developer'],
            'php' => ['PHP Developer', 'Web Developer', 'Backend Developer'],
            'laravel' => ['PHP Developer', 'Laravel Developer', 'Web Developer'],
            'html' => ['Frontend Developer', 'Web Developer', 'UI Developer'],
            'css' => ['Frontend Developer', 'UI Developer', 'Web Designer'],
            
            // DevOps & Infrastructure
            'aws' => ['DevOps Engineer', 'Cloud Engineer', 'Solutions Architect'],
            'docker' => ['DevOps Engineer', 'Infrastructure Engineer', 'Cloud Engineer'],
            'kubernetes' => ['DevOps Engineer', 'Cloud Engineer', 'Platform Engineer'],
            'ci/cd' => ['DevOps Engineer', 'Release Engineer', 'Build Engineer'],
            
            // Business & Management
            'salesforce' => ['Salesforce Administrator', 'CRM Specialist', 'Business Analyst'],
            'project management' => ['Project Manager', 'Scrum Master', 'Product Manager'],
            'agile' => ['Scrum Master', 'Agile Coach', 'Project Manager'],
            
            // Sales & Marketing
            'sales' => ['Sales Representative', 'Account Executive', 'Sales Manager'],
            'marketing' => ['Marketing Specialist', 'Digital Marketer', 'Content Marketing Manager'],
            'content writing' => ['Content Writer', 'Technical Writer', 'Content Marketing Manager'],
        ];

        $roles = [];
        foreach ($skills as $skill) {
            $skillLower = strtolower($skill);
            if (isset($skillRoleMapping[$skillLower])) {
                $roles = array_merge($roles, $skillRoleMapping[$skillLower]);
            }
        }

        return array_unique($roles);
    }

    /**
     * Map past job titles to career lanes
     */
    private function mapTitlesToRoles($jobTitles)
    {
        $titleRoleMapping = [
            'engineer' => ['Software Engineer', 'Solutions Engineer', 'Technical Engineer'],
            'developer' => ['Software Developer', 'Web Developer', 'Full Stack Developer'],
            'analyst' => ['Data Analyst', 'Business Analyst', 'Systems Analyst'],
            'manager' => ['Project Manager', 'Team Lead', 'Operations Manager'],
            'coordinator' => ['Project Coordinator', 'Event Coordinator', 'HR Coordinator'],
            'specialist' => ['Business Specialist', 'Technical Specialist', 'Systems Specialist'],
            'lead' => ['Team Lead', 'Technical Lead', 'Project Lead'],
            'architect' => ['Solutions Architect', 'Enterprise Architect', 'Data Architect'],
            'director' => ['Director of Operations', 'Director of Engineering', 'Director of Product'],
            'consultant' => ['Business Consultant', 'Technical Consultant', 'Strategy Consultant'],
            'designer' => ['UI/UX Designer', 'Graphic Designer', 'Product Designer'],
            'support' => ['Customer Support', 'Technical Support', 'Help Desk Support'],
            'administrator' => ['System Administrator', 'Database Administrator', 'IT Administrator'],
        ];

        $roles = [];
        foreach ($jobTitles as $title) {
            $titleLower = strtolower($title);
            if (isset($titleRoleMapping[$titleLower])) {
                $roles = array_merge($roles, $titleRoleMapping[$titleLower]);
            }
        }

        return array_unique($roles);
    }

    /**
     * Map sectors to career lanes
     */
    private function mapSectorsToRoles($sectors)
    {
        $sectorRoleMapping = [
            'healthcare' => ['Healthcare Analyst', 'Medical Administrator', 'Healthcare IT'],
            'finance' => ['Financial Analyst', 'Accountant', 'Risk Manager'],
            'technology' => ['Software Engineer', 'Data Engineer', 'DevOps Engineer'],
            'retail' => ['Retail Manager', 'Sales Associate', 'Merchandiser'],
            'manufacturing' => ['Operations Manager', 'Supply Chain Specialist', 'Quality Assurance'],
            'education' => ['Education Consultant', 'Curriculum Developer', 'Training Specialist'],
            'government' => ['Government Analyst', 'Policy Advisor', 'Admin Specialist'],
        ];

        $roles = [];
        foreach ($sectors as $sector) {
            $sectorLower = strtolower($sector);
            if (isset($sectorRoleMapping[$sectorLower])) {
                $roles = array_merge($roles, $sectorRoleMapping[$sectorLower]);
            }
        }

        return array_unique($roles);
    }

    /**
     * Use OpenAI to enhance role suggestions
     */
    public function enhanceRolesWithAI(PlacementProfile $profile, array $baseRoles)
    {
        try {
            $resumeText = $profile->resume_data['raw_text'] ?? '';
            $skills = implode(', ', $profile->skills ?? []);

            $prompt = "Based on the resume with skills: {$skills}, suggest up to 4 most suitable job roles that the candidate could excel at. Provide only role titles, one per line, without numbering or explanations.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a career advisor specializing in job role mapping.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $roles = $response->json('choices.0.message.content');
                $suggestedRoles = array_filter(array_map('trim', explode("\n", $roles)));
                return array_slice(array_values($suggestedRoles), 0, 4);
            }
        } catch (\Exception $e) {
            \Log::error('AI role suggestion failed: ' . $e->getMessage());
        }

        return $baseRoles;
    }

    /**
     * Standardize and normalize role suggestions
     */
    public function normalizeRoles(array $roles)
    {
        $roleNormalization = [
            'sql developer' => 'Database Developer',
            'python developer' => 'Python Developer',
            'web designer' => 'Web Designer',
            'ux designer' => 'UX Designer',
            'graphic designer' => 'Graphic Designer',
            'marketing manager' => 'Marketing Manager',
            'sales manager' => 'Sales Manager',
            'product manager' => 'Product Manager',
            'project manager' => 'Project Manager',
        ];

        $normalized = [];
        foreach ($roles as $role) {
            $roleLower = strtolower($role);
            $normalized[] = $roleNormalization[$roleLower] ?? $role;
        }

        return array_unique($normalized);
    }
}
