<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\PlacementProfile;
use App\Models\PlacementWorkflowStep;
use App\Models\JobMatch;
use App\Models\JobApplication;
use App\Models\Country;
use App\Services\Placement\ResumeParserService;
use App\Services\Placement\AIRoleMappingService;
use App\Services\Placement\JobMatchingService;
use App\Services\Placement\StandardizedProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PlacementWizardController extends Controller
{
    protected $resumeParser;
    protected $roleMapping;
    protected $jobMatching;
    protected $standardizedProfile;

    public function __construct(
        ResumeParserService $resumeParser,
        AIRoleMappingService $roleMapping,
        JobMatchingService $jobMatching,
        StandardizedProfileService $standardizedProfile
    ) {
        $this->resumeParser = $resumeParser;
        $this->roleMapping = $roleMapping;
        $this->jobMatching = $jobMatching;
        $this->standardizedProfile = $standardizedProfile;
        // No global auth middleware - handled per route
    }

    /**
     * Show the placement wizard start page
     */
    public function start()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user already has a profile
            $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();

            // If profile exists, redirect to their current step
            if ($profile) {
                return redirect()->route('placement.wizard.step', ['step' => $profile->current_step]);
            }
        }

        // Show start page to user to confirm they want to begin
        return view('placement.wizard.start');
    }

    /**
     * Create a new placement profile or session
     */
    public function create(Request $request)
    {
        // If user is authenticated, create a profile
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user already has a profile
            $existingProfile = PlacementProfile::where('user_id', $user->id)->latest()->first();
            
            if ($existingProfile) {
                // User already has a profile, redirectFV to their current step
                return redirect()->route('placement.wizard.step', ['step' => $existingProfile->current_step]);
            }

            // Create new profile
            $profile = PlacementProfile::create([
                'user_id' => $user->id,
                'current_step' => 1,
            ]);

            // Initialize workflow steps (now 8 steps)
            for ($i = 1; $i <= 8; $i++) {
                PlacementWorkflowStep::create([
                    'user_id' => $user->id,
                    'placement_profile_id' => $profile->id,
                    'step_number' => $i,
                    'status' => 'pending',
                ]);
            }

            return redirect()->route('placement.wizard.step', ['step' => 1]);
        } else {
            // User is not authenticated, start session-based flow
            session(['placement_wizard_started' => true]);
            return redirect()->route('placement.wizard.step', ['step' => 1]);
        }
    }

    /**
     * Display a specific step
     */
    public function showStep($step)
    {
        // Validate step number - now supports 8 steps
        if ($step < 1 || $step > 8) {
            abort(404);
        }

        // Check if this is step 6 or higher - require authentication
        if ($step >= 6 && !Auth::check()) {
            // Store the target step in session for post-login redirect
            session(['placement_wizard_target_step' => $step]);
            return redirect()->route('user.register')->with('placement_redirect', true);
        }

        // For steps 1-5, user may or may not be authenticated
        // If authenticated, use profile data; otherwise, use session data
        if (Auth::check()) {
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            $data = [
                'profile' => $profile,
                'step' => $step,
                'progressPercentage' => $this->calculateProgress($step),
            ];
        } else {
            // Get data from session for unauthenticated users
            $sessionData = session('placement_wizard_data', []);
            $data = [
                'profile' => null,
                'step' => $step,
                'progressPercentage' => $this->calculateProgress($step),
                'sessionData' => $sessionData,
            ];
        }
        
        // Add step-specific data
        if ($step == 2) {
            // Load active countries for location step
            $data['countries'] = Country::where('status', 1)->orderBy('country_name')->get();
        }
        if ($step == 7 && Auth::check()) {
            // Skills feedback - include extracted skills
            $extractedSkills = [];
            if (is_array($profile->resume_data) && isset($profile->resume_data['skills'])) {
                $extractedSkills = $profile->resume_data['skills'];
            }
            $data['extractedSkills'] = $extractedSkills;
        }
        if ($step == 8 && Auth::check()) {
            $suggestedRoles = $profile->suggested_roles ?? [];

            $suggestedRoles = collect($suggestedRoles)->map(function ($title) {
                return [
                    'title' => $title,
                    'match' => rand(70, 95),
                ];
            })->values()->all();

            $data['suggestedRoles'] = $suggestedRoles;
        }

        return view("placement.wizard.step-{$step}", $data);
    }

    /**
     * Handle step submission
     */
    public function submitStep(Request $request, int $step)
    {
        // Validate step range - now supports up to 8 steps
        if ($step < 1 || $step > 8) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Call the correct step handler dynamically
        $method = "submitStep{$step}";

        if (!method_exists($this, $method)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $this->$method($request);
    }

    public function submitStep1(Request $request)
    {
        try {
            $validated = $request->validate([
                'job_type' => 'required|in:hybrid,in-person,remote,no-preference',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0',
            ]);

            if (Auth::check()) {
                // Authenticated user - save to profile
                $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
                $profile->update([
                    'job_type' => $validated['job_type'],
                    'salary_min' => $validated['salary_min'] ?? null,
                    'salary_max' => $validated['salary_max'] ?? null
                ]);
                $profile->completeStep(1, $validated);
            } else {
                // Unauthenticated user - save to session
                $this->storeStepData(1, $validated);
            }

            return redirect()->route('placement.wizard.step', ['step' => 2]);
        } catch (\Exception $e) {
            Log::error('Step 1 error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    /**
     * Handle step 2: Location
     */
    public function submitStep2(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'work_permit_status' => 'required|in:yes,no-sponsorship,no-remote-only',
        ]);

        if (Auth::check()) {
            // Authenticated user - save to profile
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            $profile->update($validated);
            $profile->completeStep(2, $validated);
        } else {
            // Unauthenticated user - save to session
            $this->storeStepData(2, $validated);
        }

        return redirect()->route('placement.wizard.step', ['step' => 3]);
    }

    /**
     * Handle step 3: Industries
     */
    public function submitStep3(Request $request)
    {
        $validated = $request->validate([
            'industries' => 'required|array|min:1',
            'industries.*' => 'string',
        ]);

        if (Auth::check()) {
            // Authenticated user - save to profile
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            $profile->update($validated);
            $profile->completeStep(3, $validated);
        } else {
            // Unauthenticated user - save to session
            $this->storeStepData(3, $validated);
        }

        return redirect()->route('placement.wizard.step', ['step' => 4]);
    }

    /**
     * Handle step 4: Job Level
     */
    public function submitStep4(Request $request)
    {
        $validated = $request->validate([
            'job_level' => 'required|in:entry,mid,senior,executive,no-preference',
        ]);

        if (Auth::check()) {
            // Authenticated user - save to profile
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            $profile->update($validated);
            $profile->completeStep(4, $validated);
        } else {
            // Unauthenticated user - save to session
            $this->storeStepData(4, $validated);
        }

        return redirect()->route('placement.wizard.step', ['step' => 5]);
    }

    /**
     * Handle step 5: Languages
     */
    public function submitStep5(Request $request)
    {
        $validated = $request->validate([
            'job_languages' => 'nullable|array',
            'job_languages.*' => 'string',
        ]);

        $languages = $validated['job_languages'] ?? [];

        if (Auth::check()) {
            // Authenticated user - save to profile
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            $profile->update(['job_languages' => $languages]);
            $profile->completeStep(5, $validated);
        } else {
            // Unauthenticated user - save to session
            $this->storeStepData(5, array_merge($validated, ['job_languages' => $languages]));
        }

        return redirect()->route('placement.wizard.step', ['step' => 6]);
    }

    /**
     * Handle step 6: Resume Upload & Email
     */
    public function submitStep6(Request $request)
    {
        // Step 6+ requires authentication
        if (!Auth::check()) {
            abort(403, 'Authentication required');
        }

        try {
            $validated = $request->validate([
                'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
                'email' => 'required|email',
                'terms_agreed' => 'required|accepted',
            ], [
                'resume.required' => 'Upload Resume or Use the AI Resume Builder',
                'terms_agreed.required' => 'Please agree to the terms and conditions',
                'terms_agreed.accepted' => 'Please agree to the terms and conditions',
            ]);
            
            $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
            
            // Update email
            $profile->update(['email' => $validated['email']]);

            // Save email to marketing list if terms agreed
            if ($request->has('terms_agreed') && $request->input('terms_agreed')) {
                try {
                    $this->addToMarketingList($validated['email']);
                } catch (\Exception $e) {
                    Log::warning('Failed to add email to marketing list: ' . $e->getMessage());
                }
            }

            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $validation = $this->resumeParser->validateResume($file);
               
                if (!$validation['valid']) {
                    return back()->withErrors(['resume' => $validation['message']]);
                }

                $path = $request->file('resume')->store('resumes', 'private');
                $resumeData = $this->resumeParser->parseResume($path);
                if (!$resumeData) {
                    return back()->withErrors(['resume' => 'Failed to parse resume. Please try again.']);
                }

                $profile->update([
                    'resume_path' => $path,
                    'resume_data' => $resumeData,
                    'has_resume' => true,
                    'skills' => $resumeData['skills'] ?? [],
                    'years_experience' => $resumeData['years_experience'] ?? null,
                    'past_companies' => $resumeData['companies'] ?? [],
                    'past_sectors' => $resumeData['sectors'] ?? [],
                    'suggested_roles' => $resumeData['suggested_roles'] ?? [],
                ]);
            } else {
                $profile->update(['has_resume' => false]);
            }

            $profile->completeStep(6);
            return redirect()->route('placement.wizard.step', ['step' => 7]);
        } catch (\Exception $e) {
            Log::error('Step 6 submission error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while processing your resume. Please try again.']);
        }
    }

    /**
     * Handle step 7: Skills Feedback
     */
    public function submitStep7(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Authentication required');
        }

        $validated = $request->validate([
            'years_experience' => 'nullable|numeric|min:0|max:70',
            'extracted_skills' => 'nullable|json',
        ]);

        $profile = PlacementProfile::where('user_id', Auth::id())->latest()->firstOrFail();
        $profile->update([
            'years_experience' => $validated['years_experience'] ?? $profile->years_experience,
        ]);
        $profile->completeStep(7, $validated);

        return redirect()->route('placement.wizard.step', ['step' => 8]);
    }

    /**
     * Handle step 8: Role Selection & Generate Job Matches
     */
    public function submitStep8(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Authentication required');
        }

        try {
            $validated = $request->validate([
                'selected_roles' => 'required|array|min:1|max:4',
                'selected_roles.*' => 'string',
            ]);
            
            $user = Auth::user();
            $profile = PlacementProfile::where('user_id', $user->id)->latest()->firstOrFail();
            $profile->update(['selected_roles' => $validated['selected_roles']]);
            $profile->completeStep(8, $validated);

            // Background job scraping is now handled via API endpoint and queue jobs
            // The frontend will call /api/scraping/start to initiate background job processing

            // Create standardized profile
            $this->standardizedProfile->updateStandardizedProfile($profile);

            return response()->json([
                'success' => true,
                'message' => 'Roles saved. Job scraping will begin in the background.'
            ]);
        } catch (\Exception $e) {
            Log::error('Step 8 submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your selection. Please try again.'
            ], 500);
        }
    }

    /**
     * Add email to marketing list
     */
    private function addToMarketingList($email)
    {
        try {
            $user = Auth::user();
            
            if (class_exists('App\Models\NewsletterSubscription')) {
                \App\Models\NewsletterSubscription::firstOrCreate(
                    ['email' => $email],
                    [
                        'user_id' => Auth::id(),
                        'name' => $user->first_name . ' ' . ($user->last_name ?? ''),
                        'status' => 'subscribed',
                        'subscribed_at' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error adding to marketing list: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Store step data in session for unauthenticated users
     */
    private function storeStepData($step, $data)
    {
        $sessionData = session('placement_wizard_data', []);
        $sessionData[$step] = $data;
        session(['placement_wizard_data' => $sessionData]);
    }

    /**
     * Get session data
     */
    public function getSessionData()
    {
        return session('placement_wizard_data', []);
    }

    /**
     * Save session data to profile after user logs in
     */
    public function saveSessionDataToProfile()
    {
        if (!Auth::check()) {
            return false;
        }

        $sessionData = $this->getSessionData();
        if (empty($sessionData)) {
            return false;
        }

        $user = Auth::user();
        $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();
        
        if (!$profile) {
            // Create new profile
            $profile = PlacementProfile::create([
                'user_id' => $user->id,
                'current_step' => 6, // They logged in during step 6
            ]);

            // Initialize workflow steps
            for ($i = 1; $i <= 8; $i++) {
                PlacementWorkflowStep::create([
                    'user_id' => $user->id,
                    'placement_profile_id' => $profile->id,
                    'step_number' => $i,
                    'status' => 'pending',
                ]);
            }
        }

        // Save all session data to profile
        foreach ($sessionData as $step => $data) {
            $updateData = [];
            
            if ($step == 1) {
                $updateData = [
                    'job_type' => $data['job_type'] ?? null,
                    'salary_min' => $data['salary_min'] ?? null,
                    'salary_max' => $data['salary_max'] ?? null
                ];
            } elseif ($step == 2) {
                $updateData = [
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                    'work_permit_status' => $data['work_permit_status'] ?? null
                ];
            } elseif ($step == 3) {
                $updateData = ['industries' => $data['industries'] ?? []];
            } elseif ($step == 4) {
                $updateData = ['job_level' => $data['job_level'] ?? null];
            } elseif ($step == 5) {
                $updateData = ['job_languages' => $data['job_languages'] ?? []];
            }

            if (!empty($updateData)) {
                $profile->update($updateData);
                $profile->completeStep($step, $data);
            }
        }

        // Clear session data
        session()->forget('placement_wizard_data');

        return $profile;
    }

    /**
     * Calculate progress percentage
     */
    private function calculateProgress($step)
    {
        return ($step / 8) * 100;
    }

    /**
     * Get suggested roles for a profile
     */
    public function getSuggestedRoles($profileId)
    {
        $profile = PlacementProfile::findOrFail($profileId);
        $this->authorize('view', $profile);

        if (!$profile->suggested_roles) {
            $baseRoles = $this->roleMapping->suggestRoles($profile);
            $suggestedRoles = $this->roleMapping->enhanceRolesWithAI($profile, $baseRoles);
            $profile->update(['suggested_roles' => $suggestedRoles]);
        }

        return response()->json([
            'roles' => $profile->suggested_roles,
        ]);
    }
}
