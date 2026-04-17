<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\JobMatch;
use App\Models\PlacementProfile;
use App\Models\CoverLetter;
use App\Services\CoverLetterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoverLetterController extends Controller
{
    protected $coverLetterService;

    public function __construct(CoverLetterService $coverLetterService)
    {
        $this->middleware('auth:sanctum,web');
        $this->coverLetterService = $coverLetterService;
    }

    /**
     * Show cover letter generation page
     */
    public function generate(Request $request)
    {
        try {
            $user = Auth::user();
            $activeSubscription = $user->getActiveSubscription();

            // Check if user has cover letter feature enabled
            if (!$activeSubscription || !$activeSubscription->isActive()) {
                return redirect()->route('front.pricing')->with('error', 'Active subscription required for cover letter generation');
            }

            $hasCoverLetterFeature = $this->coverLetterService->hasCoverLetterFeature($activeSubscription);
            if (!$hasCoverLetterFeature) {
                return redirect()->route('front.pricing')->with('error', 'Cover letter feature not available on your plan');
            }

            $jobId = $request->query('job_id');
            $jobTitle = $request->query('job_title', 'Not Specified');
            $companyName = $request->query('company', 'Not Specified');

            // Get job details if job_id is provided
            $job = null;
            $jobDescription = null;
            if ($jobId) {
                $job = JobMatch::where('id', $jobId)
                    ->where('user_id', $user->id)
                    ->first();
                
                if ($job) {
                    $jobTitle = $job->job_title;
                    $companyName = $job->company_name;
                    $jobDescription = $job->job_description;
                }
            }

            $profile = $user->placementProfile;
            $selectedRoles = $profile->selected_roles ?? [];

            // Check if user has unlimited covers or limited
            $coverLetterLimit = $this->coverLetterService->getCoverLetterLimit($activeSubscription);
            $coversUsed = $this->coverLetterService->getCoversUsed($user);
            $coversRemaining = $coverLetterLimit === 'unlimited' ? null : max(0, $coverLetterLimit - $coversUsed);

            // Check if user has exceeded limit
            $hasExceededLimit = false;
            if ($coverLetterLimit !== 'unlimited' && $coversRemaining <= 0) {
                $hasExceededLimit = true;
            }

            // Fetch previous cover letters for the user (both draft and finalized)
            $previousCovers = CoverLetter::where('user_id', $user->id)
                ->whereIn('status', ['draft', 'finalized'])
                ->latest('created_at')
                ->limit(10)
                ->get()
                ->map(function ($cover) {
                    return [
                        'id' => $cover->id,
                        'job_title' => $cover->job_title,
                        'company_name' => $cover->company_name,
                        'created_at' => $cover->created_at->format('M d, Y'),
                        'status' => $cover->status,
                        'content' => $cover->content,
                        'file_url' => $cover->file_url,
                    ];
                });

            return view('placement.cover-letter.generate', [
                'jobTitle' => $jobTitle,
                'companyName' => $companyName,
                'jobDescription' => $jobDescription,
                'selectedRoles' => $selectedRoles,
                'job' => $job,
                'coverLetterLimit' => $coverLetterLimit,
                'coversUsed' => $coversUsed,
                'coversRemaining' => $coversRemaining,
                'hasExceededLimit' => $hasExceededLimit,
                'planSlug' => $activeSubscription->plan->slug ?? 'unknown',
                'previousCovers' => $previousCovers,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CoverLetterController@generate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Generate cover letter via API
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $activeSubscription = $user->getActiveSubscription();

            // Verify subscription is still active
            if (!$activeSubscription || !$activeSubscription->isActive()) {
                return response()->json(['error' => 'Subscription expired. Please renew to continue.'], 403);
            }

            // Verify cover letter feature
            $hasCoverLetterFeature = $this->coverLetterService->hasCoverLetterFeature($activeSubscription);
            if (!$hasCoverLetterFeature) {
                return response()->json(['error' => 'Cover letter feature not available on your plan.'], 403);
            }

            // Check limits before generation
            $coverLetterLimit = $this->coverLetterService->getCoverLetterLimit($activeSubscription);
            $coversUsed = $this->coverLetterService->getCoversUsed($user);

            if ($coverLetterLimit !== 'unlimited' && $coversUsed >= $coverLetterLimit) {
                return response()->json([
                    'error' => 'You have reached your cover letter limit for this billing period.',
                    'coversUsed' => $coversUsed,
                    'coversLimit' => $coverLetterLimit,
                    'needsUpgrade' => true
                ], 429);
            }

            // Validate request
            $request->validate([
                'job_title' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'job_description' => 'nullable|string',
                'selected_roles' => 'nullable|array',
                'ai_prompt' => 'nullable|string|max:1000',
            ]);

            $jobTitle = $request->input('job_title');
            $companyName = $request->input('company_name');
            $jobDescription = $request->input('job_description');
            $selectedRoles = $request->input('selected_roles', []);
            $aiPrompt = $request->input('ai_prompt');
            $jobId = $request->input('job_id');

            // Get user profile for skills/info
            $profile = $user->placementProfile;
            $userInfo = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $selectedRoles,
                'skills' => $profile->skills ?? [],
            ];

            // Generate cover letter (this is where AI API would be called)
            $coverLetterContent = $this->coverLetterService->generateCoverLetter(
                $jobTitle,
                $companyName,
                $jobDescription,
                $userInfo,
                $aiPrompt,
                $activeSubscription->plan->slug ?? 'unknown'
            );

            if (!$coverLetterContent) {
                return response()->json(['error' => 'Failed to generate cover letter. Please try again.'], 500);
            }

            // Store the cover letter in features_used but don't increment yet
            // It will only be incremented when user clicks "Finalize"
            return response()->json([
                'success' => true,
                'coverLetter' => $coverLetterContent,
                'job_title' => $jobTitle,
                'company_name' => $companyName,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CoverLetterController@store: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Finalize and save cover letter (increment usage)
     */
    public function finalize(Request $request)
    {
        try {
            $user = Auth::user();
            $activeSubscription = $user->getActiveSubscription();

            // Verify subscription
            if (!$activeSubscription || !$activeSubscription->isActive()) {
                return response()->json(['error' => 'Subscription expired.'], 403);
            }

            // Verify cover letter feature
            $hasCoverLetterFeature = $this->coverLetterService->hasCoverLetterFeature($activeSubscription);
            if (!$hasCoverLetterFeature) {
                return response()->json(['error' => 'Cover letter feature not available.'], 403);
            }

            // Final check on limits
            $coverLetterLimit = $this->coverLetterService->getCoverLetterLimit($activeSubscription);
            $coversUsed = $this->coverLetterService->getCoversUsed($user);

            if ($coverLetterLimit !== 'unlimited' && $coversUsed >= $coverLetterLimit) {
                return response()->json([
                    'error' => 'You have reached your cover letter limit.',
                    'needsUpgrade' => true
                ], 429);
            }

            // Validate request
            $request->validate([
                'cover_letter_content' => 'required|string',
                'job_title' => 'required|string',
                'company_name' => 'required|string',
                'job_id' => 'nullable|integer',
            ]);

            // Log incoming data
            $coverLetterContent = $request->input('cover_letter_content');
            $jobTitle = $request->input('job_title');
            $companyName = $request->input('company_name');
            
            Log::info('Finalize - Received cover letter content length: ' . strlen($coverLetterContent));
            Log::info('Finalize - Content preview: ' . substr($coverLetterContent, 0, 200));
            Log::info('Finalize - Job: ' . $jobTitle . ' at ' . $companyName);

            // Increment cover letter usage
            $this->coverLetterService->incrementCoverLetterUsage($user);

            // Generate download URL or return formatted content
            $downloadUrl = $this->coverLetterService->saveCoverLetterForDownload(
                $user,
                $coverLetterContent,
                $jobTitle,
                $companyName,
                $request->input('job_id')
            );

            Log::info('Finalize - Download URL generated: ' . ($downloadUrl ?? 'null'));

            return response()->json([
                'success' => true,
                'message' => 'Cover letter finalized successfully!',
                'downloadUrl' => $downloadUrl,
                'coversUsed' => $this->coverLetterService->getCoversUsed($user),
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CoverLetterController@finalize: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Download generated cover letter
     */
    public function download(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $fileName = $request->query('file');

            if (!$fileName) {
                return response()->json(['error' => 'File not found.'], 404);
            }

            // Verify file belongs to user
            $filePath = storage_path("app/cover-letters/{$user->id}/{$fileName}");

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'File not found.'], 404);
            }

            return response()->download($filePath, $fileName);
        } catch (\Exception $e) {
            Log::error('Error downloading cover letter: ' . $e->getMessage());
            return response()->json(['error' => 'Download failed. Please try again.'], 500);
        }
    }

    /**
     * List all cover letters for the user
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $coverLetters = $this->coverLetterService->getUserCoverLetters($user);

            return response()->json([
                'success' => true,
                'coverLetters' => $coverLetters,
                'totalCovers' => count($coverLetters),
            ]);
        } catch (\Exception $e) {
            Log::error('Error listing cover letters: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * View a specific cover letter
     */
    public function show(CoverLetter $coverLetter)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Verify ownership
            if ($coverLetter->user_id !== $user->id) {
                return redirect()->back()->with('error', 'Unauthorized');
            }

            return view('placement.cover-letter.show', [
                'coverLetter' => $coverLetter,
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing cover letter: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Cover letter not found.');
        }
    }

    /**
     * Delete a cover letter
     */
    public function destroy(CoverLetter $coverLetter, Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return $this->jsonErrorResponse('Unauthorized', 401, $request);
            }

            // Verify ownership
            if ($coverLetter->user_id !== $user->id) {
                return $this->jsonErrorResponse('Unauthorized', 403, $request);
            }

            // Delete the cover letter
            $deleted = $this->coverLetterService->deleteCoverLetter($coverLetter, $user);

            if (!$deleted) {
                return $this->jsonErrorResponse('Failed to delete cover letter', 500, $request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cover letter deleted successfully'
                ]);
            }

            return redirect()->route('placement.covers.index')
                ->with('success', 'Cover letter deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting cover letter: ' . $e->getMessage());
            return $this->jsonErrorResponse('An error occurred. Please try again.', 500, $request);
        }
    }

    /**
     * Duplicate a cover letter for another job
     */
    public function duplicate(CoverLetter $coverLetter, Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Verify ownership
            if ($coverLetter->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'new_job_title' => 'required|string|max:255',
                'new_company_name' => 'required|string|max:255',
            ]);

            // Create a new cover letter based on the original
            $newCoverLetter = CoverLetter::create([
                'user_id' => $user->id,
                'job_title' => $validated['new_job_title'],
                'company_name' => $validated['new_company_name'],
                'content' => $coverLetter->content, // Copy content
                'status' => 'draft', // New one starts as draft
            ]);

            Log::info('Cover letter duplicated', [
                'user_id' => $user->id,
                'original_id' => $coverLetter->id,
                'new_id' => $newCoverLetter->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cover letter duplicated successfully',
                'cover_letter_id' => $newCoverLetter->id,
                'edit_url' => route('placement.covers.generate', [
                    'job_title' => $newCoverLetter->job_title,
                    'company' => $newCoverLetter->company_name,
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('Error duplicating cover letter: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to duplicate cover letter'], 500);
        }
    }

    /**
     * Helper method for JSON error responses
     */
    private function jsonErrorResponse($message, $status, Request $request)
    {
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['error' => $message], $status);
        }

        return redirect()->back()->with('error', $message);
    }
}
