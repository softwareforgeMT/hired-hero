<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\PlacementProfile;
use App\Models\Resume;
use App\Services\Placement\StripePaymentService;
use App\Services\Placement\ResumeGeneratorService;
use App\Mail\ResumeCreatedMail;
use App\Models\SubPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ResumeBuilderController extends Controller
{
    protected $stripePayment;
    protected $resumeGenerator;

    public function __construct(
        StripePaymentService $stripePayment,
        ResumeGeneratorService $resumeGenerator
    ) {
        $this->stripePayment = $stripePayment;
        $this->resumeGenerator = $resumeGenerator;
        $this->middleware('auth:sanctum,web')->except(['validatePromoCode']);
    }

    /**
     * Show the resume builder subscription page
     */

    public function pricing()
    {
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        $hasActiveSubscription = $activeSubscription && 
                                 $activeSubscription->isActive() && 
                                 $activeSubscription->plan && 
                                 $activeSubscription->plan->slug !== 'free-plan';

        return view('placement.resume-builder.index', [
            'hasActiveSubscription' => $hasActiveSubscription,

            'plans' => SubPlan::where('status', 1)->get(),
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();

        // Check if user already has active subscription from UserSubscription table
        $activeSubscription = $user->getActiveSubscription();
        $hasActiveSubscription = $activeSubscription && 
                                 $activeSubscription->isActive() && 
                                 $activeSubscription->plan && 
                                 $activeSubscription->plan->slug !== 'free-plan';

        if (!$profile) {
            return redirect()->route('placement.step');
        }

        return view('placement.resume-builder.form', [
            'profile' => $profile,
            'hasActiveSubscription' => $hasActiveSubscription,
        ]);
    }

    /**
     * Initiate checkout for Resume Builder subscription
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:weekly,monthly',
            'promo_code' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $promoCode = null;

        // Validate and get promo code if provided
        if (!empty($validated['promo_code'])) {
            $code = strtoupper($validated['promo_code']);
            $promoCode = \App\Models\PromoCode::where('code', $code)->first();
            
            // Comprehensive promo code validation
            if (!$promoCode) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'Promo code not found.']);
            }

            if (!$promoCode->active) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'This promo code has been deactivated.']);
            }

            // Check if code has expired
            if ($promoCode->hasExpired()) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'This promo code has expired and can no longer be used.']);
            }

            // Check if code has reached max usage
            if ($promoCode->used_count >= $promoCode->max_usage) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'This promo code has reached its usage limit.']);
            }

            // Check if user is assigned to this code
            $userAssignment = $promoCode->users()->where('user_id', $user->id)->first();
            
            if (!$userAssignment) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'This promo code cannot be used by you.']);
            }

            // Check if user has already used this code
            if ($userAssignment->pivot->used) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'You have already used this promo code.']);
            }

            // Final validation: check if code is still valid
            if (!$promoCode->canUserUse($user)) {
                return back()
                    ->withInput()
                    ->withErrors(['promo_code' => 'This promo code cannot be applied. Please verify and try again.']);
            }
        }

        // Create checkout session with validated promo code
        $checkout = $this->stripePayment->createCheckoutSession($user, $validated['plan'], $promoCode);
        if (!$checkout) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create checkout session. Please try again.']);
        }

        // Store session ID and promo code in session for retrieval after redirect
        session(['stripe_checkout_session_id' => $checkout['session_id']]);
        if ($promoCode) {
            session(['stripe_promo_code_id' => $promoCode->id]);
        }

        return redirect()->away($checkout['url']);
    }

    /**
     * Handle successful checkout
     */
    public function checkoutSuccess(Request $request)
    {
        try{
            // Get session ID from session storage (or fallback to query parameter)
            $sessionId = session('stripe_checkout_session_id') ?? $request->get('session_id');
            
            // Clear the session data
            session()->forget('stripe_checkout_session_id');
            
            // Validate session ID
            if (!$sessionId || strpos($sessionId, '{') === 0) {
                Log::error('Invalid or placeholder session ID in checkout success', [
                    'session_id' => $sessionId,
                    'user_id' => Auth::id(),
                ]);
                return redirect()->route('placement.wizard.step', ['step' => 6])
                    ->withErrors(['error' => 'Invalid checkout session. Please try again.']);
            }

            $user = Auth::user();
            $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();

            // Get session metadata
            $session = $this->stripePayment->getCheckoutSession($sessionId);

            if (!$session) {
                Log::error('Stripe session not found after checkout', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ]);
                return redirect()->route('placement.wizard.step', ['step' => 6])
                    ->withErrors(['error' => 'Unable to verify payment. Please contact support with your session ID: ' . $sessionId]);
            }

            if (!$session->metadata) {
                Log::error('Stripe session missing metadata', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ]);
                return redirect()->route('placement.wizard.step', ['step' => 6])
                    ->withErrors(['error' => 'Invalid session metadata. Please contact support.']);
            }
            

            // Handle successful payment
            $paymentHandled = $this->stripePayment->handleSuccessfulPayment(
                $sessionId
            );

            if (!$paymentHandled) {
                Log::error('Failed to process successful payment', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'metadata' => $session->metadata,
                ]);
                return redirect()->route('placement.wizard.step', ['step' => 6])
                    ->withErrors(['error' => 'Payment processing failed. Please try again or contact support.']);
            }

            // Redirect to resume builder form
            return redirect()->route('resume-builder.form')
                ->with('success', 'Payment successful! Let\'s build your AI-powered resume.');
        
        } catch (\Exception $e) {
            Log::error('Checkout success handling failed', [
                'user_id' => Auth::id(),
                'session_id' => $sessionId ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('placement.wizard.step', ['step' => 6])
                ->withErrors(['error' => 'An error occurred while processing your payment. Please contact support.']);
        }
    }

    /**
     * Show the resume builder form
     */
    public function form()
    {
        $user = Auth::user();
        $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();

        // Check if user has active subscription from UserSubscription table
        $activeSubscription = $user->getActiveSubscription();
        $hasActiveSubscription = $activeSubscription && 
                                 $activeSubscription->isActive() && 
                                 $activeSubscription->plan && 
                                 $activeSubscription->plan->slug !== 'free-plan';
        
        // Allow free plan users who signed up less than 14 days ago to use resume builder as trial
        $isFreeTrialEligible = false;
        if (!$hasActiveSubscription && $activeSubscription && 
            $activeSubscription->plan && 
            $activeSubscription->plan->slug === 'free-plan') {
            // Check if user was created less than 14 days ago
            $daysSinceCreated = $user->created_at->diffInDays(now());
            $isFreeTrialEligible = $daysSinceCreated < 14;
        }
        
        // Override to allow trial users to use resume builder
        $hasActiveSubscription = $hasActiveSubscription || $isFreeTrialEligible;
        
        if (!$hasActiveSubscription) {
            return redirect()->route('front.pricing')
                ->withErrors(['error' => 'You need an active subscription to use the Resume Builder.']);
        }

        if (!$profile) {
            return redirect()->route('placement.step');
        }

        return view('placement.resume-builder.form', [
            'profile' => $profile,
            'templates' => $this->resumeGenerator->getAvailableTemplates(),
        ]);
    }

    /**
     * Store the generated resume
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'nullable|string|max:20',
                'professional_title' => 'nullable|string|max:255',
                'professional_summary' => 'nullable|string|max:2000',
                'template' => 'required|in:modern,classic,minimalist,professional,creative',
                'work_experience' => 'nullable|array',
                'work_experience.*.job_title' => 'required_with:work_experience|string',
                'work_experience.*.company' => 'required_with:work_experience|string',
                'work_experience.*.start_date' => 'required_with:work_experience|date',
                'work_experience.*.end_date' => 'nullable|date',
                'work_experience.*.location' => 'nullable|string',
                'work_experience.*.description' => 'nullable|string',
                'work_experience.*.currently_working' => 'nullable|in:0,1',
                'education' => 'nullable|array',
                'education.*.degree' => 'required_with:education|string',
                'education.*.institution' => 'required_with:education|string',
                'education.*.field_of_study' => 'nullable|string',
                'education.*.graduation_date' => 'nullable|date',
                'education.*.description' => 'nullable|string',
                'skills' => 'nullable|array',
                'skills.*' => 'nullable|string|max:100',
            ]);

            $user = Auth::user();
            $profile = PlacementProfile::where('user_id', $user->id)->latest()->first();

            // Check subscription is still active from UserSubscription table
            $activeSubscription = $user->getActiveSubscription();
            $hasActiveSubscription = $activeSubscription && 
                                     $activeSubscription->isActive() && 
                                     $activeSubscription->plan && 
                                     $activeSubscription->plan->slug !== 'free-plan';
            
            // Allow free plan users who signed up less than 14 days ago to use resume builder as trial
            $isFreeTrialEligible = false;
            if (!$hasActiveSubscription && $activeSubscription && 
                $activeSubscription->plan && 
                $activeSubscription->plan->slug === 'free-plan') {
                // Check if user was created less than 14 days ago
                $daysSinceCreated = $user->created_at->diffInDays(now());
                $isFreeTrialEligible = $daysSinceCreated < 14;
            }
            
            // Override to allow trial users to use resume builder
            $hasActiveSubscription = $hasActiveSubscription || $isFreeTrialEligible;
            
            if (!$hasActiveSubscription) {
                return back()->withErrors(['error' => 'Your subscription has expired. Please renew to continue.']);
            }

            // Generate resume
            $resume = $this->resumeGenerator->generateResume(
                $user,
                $profile,
                $validated['template'],
                $validated
            );

            if (!$resume) {
                return back()->withErrors(['error' => 'Failed to generate resume. Please try again.']);
            }

            // Update profile
            $profile->update([
                'has_paid_resume_builder' => true,
                'active_subscription_id' => $this->stripePayment->getUserActiveSubscription($user)?->id,
            ]);

            // Send email to user
            try {
                Mail::to($user->email)->send(new ResumeCreatedMail($user, $resume));
            } catch (\Exception $e) {
                Log::error('Failed to send resume creation email', [
                    'user_id' => $user->id,
                    'resume_id' => $resume->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('resume-builder.view', ['resume' => $resume->id])
                ->with('success', 'Resume created successfully! An email has been sent to you.');
        } catch (\Exception $e) {
            Log::error('Resume creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Failed to create resume. Please try again.']);
        }
    }

    /**
     * View a generated resume
     */
    public function view(Resume $resume)
    {
        // Ensure resume exists and has an ID
        if (!$resume || !$resume->id) {
            Log::warning('Resume view attempted with invalid resume', [
                'user_id' => Auth::id(),
                'resume_id' => $resume->id ?? 'null',
            ]);
            return redirect()->route('placement.wizard.step', ['step' => 6])
                ->withErrors(['error' => 'Resume not found.']);
        }

        // $this->authorize('view', $resume);

        return view('placement.resume-builder.view', [
            'resume' => $resume,
        ]);
    }

    /**
     * Display resume PDF as HTML (for preview in iframe)
     */
    public function preview(Resume $resume)
    {
        // Ensure resume exists with valid ID
        if (!$resume || !$resume->id) {
            Log::warning('Resume preview attempted with invalid resume', [
                'user_id' => Auth::id(),
                'resume_id' => $resume->id ?? 'null',
            ]);
            return back()->withErrors(['error' => 'Resume not found.']);
        }

        $this->authorize('view', $resume);

        // Get template data
        $templateName = $resume->template_name;
        $templateData = $resume->data;

        // Render the template as HTML
        return view("placement.resume-templates.{$templateName}", $templateData);
    }

    /**
     * Download resume PDF
     */
    public function download(Resume $resume)
    {
        // Ensure resume exists with valid ID
        if (!$resume || !$resume->id) {
            Log::warning('Resume download attempted with invalid resume', [
                'user_id' => Auth::id(),
                'resume_id' => $resume->id ?? 'null',
            ]);
            return back()->withErrors(['error' => 'Resume not found.']);
        }

        $this->authorize('view', $resume);

        if (!$resume->fileExists()) {
            return back()->withErrors(['error' => 'Resume file not found.']);
        }

        return response()->download(
            storage_path('app/private/' . $resume->file_path),
            $resume->title . '.pdf'
        );
    }

    /**
     * Edit resume form
     */
    public function edit(Resume $resume)
    {
        if (!$resume || !$resume->id) {
            Log::warning('Resume edit attempted with invalid resume', [
                'user_id' => Auth::id(),
                'resume_id' => $resume->id ?? 'null',
            ]);
            return redirect()->route('placement.wizard.step', ['step' => 6])
                ->withErrors(['error' => 'Resume not found.']);
        }

        $this->authorize('update', $resume);

        return view('placement.resume-builder.edit', [
            'resume' => $resume,
            'templates' => $this->resumeGenerator->getAvailableTemplates(),
        ]);
    }

    /**
     * Update resume
     */
    public function update(Request $request, Resume $resume)
    {
        $this->authorize('update', $resume);

        // Validate input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'professional_title' => 'nullable|string|max:255',
            'professional_summary' => 'nullable|string|max:2000',
            'template' => 'required|in:modern,classic,minimalist,professional,creative',
            'work_experience' => 'nullable|array',
            'education' => 'nullable|array',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
        ]);

        // Update resume data
        $this->resumeGenerator->updateResume($resume, $validated);

        return redirect()->route('resume-builder.view', ['resume' => $resume->id])
            ->with('success', 'Resume updated successfully! View your updated resume below.');
    }

    /**
     * Delete resume
     */
    public function destroy(Resume $resume, Request $request)
    {
        if (!$resume || !$resume->id) {
            Log::warning('Resume deletion attempted with invalid resume', [
                'user_id' => Auth::id(),
                'resume_id' => $resume->id ?? 'null',
            ]);
            
            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['error' => 'Resume not found.'], 404);
            }
            
            return redirect()->route('placement.wizard.step', ['step' => 6])
                ->withErrors(['error' => 'Resume not found.']);
        }

        $this->authorize('delete', $resume);

        if ($this->resumeGenerator->deleteResume($resume)) {
            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Resume deleted successfully.'
                ]);
            }
            
            return redirect()->route('placement.wizard.step', ['step' => 6])
                ->with('success', 'Resume deleted successfully.');
        }

        // Check if this is an AJAX request
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['error' => 'Failed to delete resume.'], 500);
        }
        
        return back()->withErrors(['error' => 'Failed to delete resume.']);
    }

    /**
     * Validate promo code and return discount preview (AJAX)
     * Works for both authenticated and unauthenticated users
     * Returns only the discount percentage - user can apply to any plan
     */
    public function validatePromoCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50',
            ]);

            $code = strtoupper($validated['code']);
            $isAuthenticated = Auth::check();
            $user = $isAuthenticated ? Auth::user() : null;

            // Find promo code
            $promoCode = \App\Models\PromoCode::where('code', $code)->first();

            if (!$promoCode) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo code not found.',
                ], 404);
            }

            // Check if code is active
            if (!$promoCode->active) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This promo code has been deactivated.',
                ], 422);
            }

            // Check if code has expired
            if ($promoCode->hasExpired()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This promo code has expired and can no longer be used.',
                    'expired' => true,
                ], 422);
            }

            // Check if code has reached max usage
            if ($promoCode->used_count >= $promoCode->max_usage) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This promo code has reached its usage limit.',
                ], 422);
            }

            // Additional checks only for authenticated users
            if ($isAuthenticated && $user) {
                // Check if user is assigned to this code
                $userAssignment = $promoCode->users()->where('user_id', $user->id)->first();
                
                if (!$userAssignment) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'This promo code cannot be used by you.',
                    ], 422);
                }

                // Check if user has already used this code
                if ($userAssignment->pivot->used) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'You have already used this promo code.',
                    ], 422);
                }
            }

            // Return valid promo code with discount percentage
            // Frontend will apply this discount to all visible plans
            return response()->json([
                'valid' => true,
                'code' => $promoCode->code,
                'discount_percentage' => $promoCode->discount_percentage,
                'message' => "Promo code '{$code}' applied! {$promoCode->discount_percentage}% discount on all plans",
                'expires_at' => $promoCode->expires_at?->format('M d, Y'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Promo code validation error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'valid' => false,
                'message' => 'An error occurred while validating the promo code.',
            ], 500);
        }
    }
}

