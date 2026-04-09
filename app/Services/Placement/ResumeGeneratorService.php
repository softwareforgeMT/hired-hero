<?php

namespace App\Services\Placement;

use App\Models\User;
use App\Models\PlacementProfile;
use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResumeGeneratorService
{
    /**
     * AI Enhancement service
     */
    protected AIResumeEnhancementService $aiService;

    /**
     * Constructor
     */
    public function __construct(AIResumeEnhancementService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Available resume templates
     */
    protected array $templates = [
        'modern' => 'Modern - Clean & Contemporary',
        'classic' => 'Classic - Professional & Traditional',
        'minimalist' => 'Minimalist - Simple & Elegant',
        'professional' => 'Professional - Corporate Style',
        'creative' => 'Creative - Design-Focused',
    ];

    /**
     * Get all available templates
     */
    public function getAvailableTemplates(): array
    {
        return $this->templates;
    }

    /**
     * Generate a resume from user data
     */
    public function generateResume(
        User $user,
        PlacementProfile $profile,
        string $templateName,
        array $resumeData
    ): ?Resume {
        try {
            // Validate template
            if (!isset($this->templates[$templateName])) {
                throw new \Exception("Invalid template: {$templateName}");
            }

            // Prepare resume data
            $preparedData = $this->prepareResumeData($user, $profile, $resumeData);

            // Enhance content with AI
            $preparedData = $this->aiService->enhanceResumeData($preparedData);

            // Generate PDF
            $pdfPath = $this->generatePdf($templateName, $preparedData);

            if (!$pdfPath) {
                return null;
            }

            // Create resume record
            $resume = Resume::create([
                'user_id' => $user->id,
                'placement_profile_id' => $profile->id,
                'template_name' => $templateName,
                'title' => $preparedData['personal_info']['full_name'] . ' - Resume',
                'file_path' => $pdfPath,
                'file_url' => Storage::disk('private')->url($pdfPath),
                'data' => $preparedData,
                'status' => 'active',
            ]);

            // Update placement profile
            $profile->update([
                'resume_path' => $pdfPath,
                'has_resume' => true,
            ]);

            return $resume;
        } catch (\Exception $e) {
            Log::error('Resume generation failed', [
                'user_id' => $user->id,
                'profile_id' => $profile->id,
                'template' => $templateName,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Prepare resume data from user and profile
     */
    protected function prepareResumeData(User $user, PlacementProfile $profile, array $formData): array
    {
        return [
            'personal_info' => [
                'full_name' => $formData['full_name'] ?? $user->name ?? 'Your Name',
                'email' => $formData['email'] ?? $profile->email ?? $user->email,
                'phone' => $formData['phone'] ?? '',
                'location' => $formData['location'] ?? ($profile->city . ', ' . $profile->country),
                'professional_title' => $formData['professional_title'] ?? '',
                'summary' => $formData['professional_summary'] ?? '',
            ],
            'work_experience' => array_map(function ($job) {
                return [
                    'job_title' => $job['job_title'] ?? '',
                    'company' => $job['company'] ?? '',
                    'location' => $job['location'] ?? '',
                    'start_date' => $job['start_date'] ?? '',
                    'end_date' => $job['end_date'] ?? 'Present',
                    'currently_working' => $job['currently_working'] ?? false,
                    'description' => $job['description'] ?? '',
                ];
            }, $formData['work_experience'] ?? []),
            'education' => array_map(function ($edu) {
                return [
                    'degree' => $edu['degree'] ?? '',
                    'institution' => $edu['institution'] ?? '',
                    'field' => $edu['field_of_study'] ?? '',
                    'graduation_date' => $edu['graduation_date'] ?? '',
                    'description' => $edu['description'] ?? '',
                ];
            }, $formData['education'] ?? []),
            'skills' => $formData['skills'] ?? $profile->skills ?? [],
            'languages' => $profile->job_languages ?? [],
            'certifications' => array_map(function ($cert) {
                return [
                    'name' => $cert['name'] ?? '',
                    'issuer' => $cert['issuer'] ?? '',
                    'issue_date' => $cert['issue_date'] ?? '',
                    'expiry_date' => $cert['expiry_date'] ?? '',
                ];
            }, $formData['certifications'] ?? []),
            'years_experience' => $profile->years_experience ?? 0,
        ];
    }

    /**
     * Generate PDF from template
     */
    protected function generatePdf(string $templateName, array $data): ?string
    {
        try {
            // Render the template
            $html = view("placement.resume-templates.{$templateName}", $data)->render();

            // Generate PDF
            $pdf = Pdf::loadHTML($html);
            
            // Store PDF
            $filename = 'resume-' . Str::random(10) . '.pdf';
            $path = 'resumes/' . $filename;

            Storage::disk('private')->put($path, $pdf->output());

            return $path;
        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'template' => $templateName,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get resume by ID
     */
    public function getResume(int $resumeId): ?Resume
    {
        return Resume::find($resumeId);
    }

    /**
     * Get user's latest resume
     */
    public function getUserLatestResume(User $user): ?Resume
    {
        return Resume::where('user_id', $user->id)
            ->active()
            ->latest()
            ->first();
    }

    /**
     * Archive old resumes and set new one as active
     */
    public function setActiveResume(Resume $resume): bool
    {
        try {
            // Archive other resumes for this user
            Resume::where('user_id', $resume->user_id)
                ->where('id', '!=', $resume->id)
                ->update(['status' => 'archived']);

            // Set this one as active
            $resume->update(['status' => 'active']);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to set active resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update resume data
     */
    public function updateResume(Resume $resume, array $updatedData): bool
    {
        try {
            // Get the user and profile from the resume
            $user = $resume->user;
            $profile = $resume->placementProfile;

            // Prepare the updated data
            $preparedData = $this->prepareResumeData($user, $profile, $updatedData);

            // Enhance content with AI
            $enhancedData = $this->aiService->enhanceResumeData($preparedData);

            // Regenerate PDF with enhanced data
            $pdfPath = $this->generatePdf($resume->template_name, $enhancedData);

            if ($pdfPath) {
                // Delete old file
                if ($resume->file_path && Storage::disk('private')->exists($resume->file_path)) {
                    Storage::disk('private')->delete($resume->file_path);
                }

                // Update resume with new data and file path
                $resume->update([
                    'data' => $enhancedData,
                    'file_path' => $pdfPath,
                    'file_url' => Storage::disk('private')->url($pdfPath),
                ]);
            } else {
                // If PDF generation fails, just update the data
                $resume->update([
                    'data' => $enhancedData,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Delete a resume
     */
    public function deleteResume(Resume $resume): bool
    {
        try {
            // Delete file
            if ($resume->file_path && Storage::disk('private')->exists($resume->file_path)) {
                Storage::disk('private')->delete($resume->file_path);
            }

            // Delete record
            $resume->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
