<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationTrackerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display application tracker
     */
    public function index()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with('jobMatch')
            ->orderByDesc('last_activity_at')
            ->paginate(20);

        $stats = [
            'total_applications' => JobApplication::where('user_id', Auth::id())->count(),
            'to_review' => JobApplication::where('user_id', Auth::id())->where('status', 'to-review')->count(),
            'ready' => JobApplication::where('user_id', Auth::id())->where('status', 'ready')->count(),
            'applied' => JobApplication::where('user_id', Auth::id())->where('status', 'applied')->count(),
            'callback' => JobApplication::where('user_id', Auth::id())->where('status', 'callback')->count(),
            'interview' => JobApplication::where('user_id', Auth::id())->where('status', 'interview')->count(),
            'offer' => JobApplication::where('user_id', Auth::id())->where('status', 'offer')->count(),
            'hired' => JobApplication::where('user_id', Auth::id())->where('status', 'hired')->count(),
        ];

        return view('placement.applications.tracker', [
            'applications' => $applications,
            'stats' => $stats,
        ]);
    }

    /**
     * Show form to create new application
     */
    public function create()
    {
        $statuses = [
            'to-review' => 'To Review',
            'ready' => 'Ready',
            'applied' => 'Applied',
            'callback' => 'Callback',
            'interview' => 'Interview',
            'offer' => 'Offer',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
            'archived' => 'Archived',
        ];

        return view('placement.applications.create', [
            'statuses' => $statuses,
        ]);
    }

    /**
     * Store manually added application
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => 'required|in:to-review,ready,applied,callback,interview,offer,hired,rejected,archived',
            'applied_at' => 'required|date|before_or_equal:today',
            'interview_date' => 'nullable|date|after:applied_at',
            'interview_notes' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::create([
            'user_id' => Auth::id(),
            'job_title' => $validated['job_title'],
            'company_name' => $validated['company_name'],
            'job_url' => $validated['job_url'] ?? null,
            'status' => $validated['status'],
            'applied_at' => $validated['applied_at'],
            'last_activity_at' => now(),
            'interview_date' => $validated['interview_date'] ?? null,
            'interview_notes' => $validated['interview_notes'] ?? null,
        ]);

        return redirect()
            ->route('placement.applications.index')
            ->with('success', "Application for {$validated['job_title']} at {$validated['company_name']} added successfully!");
    }

    /**
     * Show form to edit application
     */
    public function edit($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('update', $application);

        $statuses = [
            'to-review' => 'To Review',
            'ready' => 'Ready',
            'applied' => 'Applied',
            'callback' => 'Callback',
            'interview' => 'Interview',
            'offer' => 'Offer',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
            'archived' => 'Archived',
        ];

        return view('placement.applications.edit', [
            'application' => $application,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update application
     */
    public function update(Request $request, $applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('update', $application);

        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => 'required|in:to-review,ready,applied,callback,interview,offer,hired,rejected,archived',
            'applied_at' => 'required|date|before_or_equal:today',
            'interview_date' => 'nullable|date|after:applied_at',
            'interview_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'job_title' => $validated['job_title'],
            'company_name' => $validated['company_name'],
            'job_url' => $validated['job_url'] ?? null,
            'status' => $validated['status'],
            'applied_at' => $validated['applied_at'],
            'last_activity_at' => now(),
            'interview_date' => $validated['interview_date'] ?? null,
            'interview_notes' => $validated['interview_notes'] ?? null,
        ]);

        return redirect()
            ->route('placement.applications.index')
            ->with('success', "Application updated successfully!");
    }

    /**
     * Delete application
     */
    public function destroy($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('delete', $application);

        $jobTitle = $application->job_title;
        $companyName = $application->company_name;

        $application->delete();

        return redirect()
            ->route('placement.applications.index')
            ->with('success', "Application for {$jobTitle} at {$companyName} has been deleted.");
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $applicationId)
    {
        $validated = $request->validate([
            'status' => 'required|in:to-review,ready,applied,callback,interview,offer,hired,rejected,archived',
            'notes' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('update', $application);

        $application->updateStatus($validated['status'], $validated['notes'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully',
        ]);
    }

    /**
     * Get application details
     */
    public function show($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('view', $application);

        return view('placement.applications.show', [
            'application' => $application,
        ]);
    }

    /**
     * Filter applications
     */
    public function filter(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:to-review,ready,applied,callback,interview,offer,hired,rejected,archived',
            'company' => 'nullable|string|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = JobApplication::where('user_id', Auth::id())
            ->with('jobMatch');

        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }

        if ($request->filled('company')) {
            $query->where('company_name', 'like', '%' . $validated['company'] . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('applied_at', '>=', $validated['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('applied_at', '<=', $validated['date_to']);
        }

        $applications = $query->orderByDesc('last_activity_at')->paginate(20);

        // Calculate stats for all user applications (not just filtered)
        $stats = [
            'total_applications' => JobApplication::where('user_id', Auth::id())->count(),
            'to_review' => JobApplication::where('user_id', Auth::id())->where('status', 'to-review')->count(),
            'ready' => JobApplication::where('user_id', Auth::id())->where('status', 'ready')->count(),
            'applied' => JobApplication::where('user_id', Auth::id())->where('status', 'applied')->count(),
            'callback' => JobApplication::where('user_id', Auth::id())->where('status', 'callback')->count(),
            'interview' => JobApplication::where('user_id', Auth::id())->where('status', 'interview')->count(),
            'offer' => JobApplication::where('user_id', Auth::id())->where('status', 'offer')->count(),
            'hired' => JobApplication::where('user_id', Auth::id())->where('status', 'hired')->count(),
        ];

        return view('placement.applications.tracker', [
            'applications' => $applications,
            'filters' => $validated,
            'stats' => $stats,
        ]);
    }

    /**
     * Archive old applications
     */
    public function archiveOld(Request $request)
    {
        $daysOld = $request->input('days', 90);

        JobApplication::where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->whereDate('last_activity_at', '<', now()->subDays($daysOld))
            ->update(['status' => 'archived']);

        return response()->json([
            'success' => true,
            'message' => 'Old applications archived successfully',
        ]);
    }

    /**
     * Get pipeline statistics
     */
    public function getPipelineStats()
    {
        $user = Auth::user();
        $stats = [
            'to_review' => JobApplication::where('user_id', $user->id)->where('status', 'to-review')->count(),
            'ready' => JobApplication::where('user_id', $user->id)->where('status', 'ready')->count(),
            'applied' => JobApplication::where('user_id', $user->id)->where('status', 'applied')->count(),
            'callback' => JobApplication::where('user_id', $user->id)->where('status', 'callback')->count(),
            'interview' => JobApplication::where('user_id', $user->id)->where('status', 'interview')->count(),
            'offer' => JobApplication::where('user_id', $user->id)->where('status', 'offer')->count(),
            'hired' => JobApplication::where('user_id', $user->id)->where('status', 'hired')->count(),
        ];

        return response()->json($stats);
    }
}
