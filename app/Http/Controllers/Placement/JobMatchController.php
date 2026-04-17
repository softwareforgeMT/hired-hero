<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\PlacementProfile;
use App\Models\JobMatch;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum,web');
    }

    /**
     * Display job matches for the user
     */
    public function index()
    {
        $user = Auth::user();
        $profile = PlacementProfile::where('user_id', $user->id)->where('is_completed', true)->latest()->firstOrFail();

        // Check access
        if (!$profile->canAccessJobMatches()) {
            return redirect()->route('placement.subscription')->with('message', 'Please subscribe to view job matches');
        }

        $jobMatches = $profile->jobMatches()
            ->orderByDesc('match_score')
            ->paginate(20);

        return view('placement.job-matches.index', [
            'profile' => $profile,
            'jobMatches' => $jobMatches,
        ]);
    }

    /**
     * Show job match details
     */
    public function show($jobMatchId)
    {
        $jobMatch = JobMatch::findOrFail($jobMatchId);
        $this->authorize('view', $jobMatch);

        $application = JobApplication::where('job_match_id', $jobMatch->id)
            ->where('user_id', Auth::id())
            ->first();

        return view('placement.job-matches.show', [
            'jobMatch' => $jobMatch,
            'application' => $application,
        ]);
    }

    /**
     * Filter job matches
     */
    public function filter(Request $request)
    {
        $validated = $request->validate([
            'job_source' => 'nullable|string|in:indeed,linkedin,glassdoor,workopolis,workday,wellfound',
            'min_match_score' => 'nullable|integer|min:0|max:100',
            'max_match_score' => 'nullable|integer|min:0|max:100',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
        ]);
        $user = Auth::user();
        $profile = PlacementProfile::where('user_id', $user->id)->latest()->firstOrFail();

        $query = $profile->jobMatches();

        if ($request->filled('job_source')) {
            $query->where('source', $validated['job_source']);
        }

        if ($request->filled('min_match_score')) {
            $query->where('match_score', '>=', $validated['min_match_score']);
        }

        if ($request->filled('max_match_score')) {
            $query->where('match_score', '<=', $validated['max_match_score']);
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $validated['salary_min']);
        }

        if ($request->filled('salary_max')) {
            $query->where('salary_max', '<=', $validated['salary_max']);
        }

        // Always sort by match_score descending
        $query->orderByDesc('match_score');

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $jobMatches = $query->get();
            return response()->json([
                'jobs' => $jobMatches->map(fn($job) => [
                    'id' => $job->id,
                    'job_title' => $job->job_title,
                    'company_name' => $job->company_name,
                    'source' => $job->source,
                    'location' => $job->location,
                    'match_score' => $job->match_score,
                    'posted_date' => $job->posted_date,
                    'job_url' => $job->job_url,
                    'job_type' => $job->job_type,
                    'matched_skills' => $job->matched_skills ?? [],
                ])
            ]);
        }

        // For regular requests, paginate
        $jobMatches = $query->paginate(20);
        return view('placement.job-matches.index', [
            'profile' => $profile,
            'jobMatches' => $jobMatches,
            'filters' => $validated,
        ]);
    }

    /**
     * Apply for a job
     */
    public function applyForJob(Request $request, $jobMatchId)
    {
        $jobMatch = JobMatch::findOrFail($jobMatchId);
        $this->authorize('view', $jobMatch);

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:5000',
            'use_ai_cover_letter' => 'nullable|boolean',
        ]);

        // Check if already applied
        $existingApplication = JobApplication::where('job_match_id', $jobMatch->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this job',
            ], 422);
        }

        $application = JobApplication::create([
            'user_id' => Auth::id(),
            'job_match_id' => $jobMatch->id,
            'job_title' => $jobMatch->job_title,
            'company_name' => $jobMatch->company_name,
            'job_url' => $jobMatch->job_url,
            'status' => 'applied',
            'applied_at' => now(),
            'last_activity_at' => now(),
            'cover_letter' => $validated['cover_letter'] ?? null,
            'used_ai_cover_letter' => $validated['use_ai_cover_letter'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'application' => $application,
        ]);
    }

    /**
     * Delete a job match (AJAX supported)
     */
    public function destroy($jobMatchId, Request $request)
    {
        $jobMatch = JobMatch::findOrFail($jobMatchId);
        
        // Authorize deletion
        if ($jobMatch->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $jobMatch->delete();
            
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Job match removed successfully.'
                ]);
            }

            return back()->with('success', 'Job match removed successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to remove job match'
                ], 500);
            }
            
            return back()->withErrors('Failed to remove job match');
        }
    }

    /**
     * Get match quality badge
     */
    public function getMatchQuality($jobMatchId)
    {
        $jobMatch = JobMatch::findOrFail($jobMatchId);
        
        return response()->json([
            'quality' => $jobMatch->getMatchQuality(),
            'score' => $jobMatch->getMatchPercentage(),
            'matched_skills' => $jobMatch->matched_skills,
            'missing_skills' => $jobMatch->missing_skills,
        ]);
    }
}
