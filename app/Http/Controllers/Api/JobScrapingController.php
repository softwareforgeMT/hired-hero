<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ScrapeJobs;
use App\Models\ScrapingProgress;
use App\Models\JobMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobScrapingController extends Controller
{
    /**
     * Start background job scraping
     */
    public function startScraping(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get selected roles from request
            $selectedRoles = $request->input('selected_roles', []);

            // Check if already scraping
            $existingProgress = ScrapingProgress::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->first();

            if ($existingProgress) {
                return response()->json([
                    'success' => true,
                    'message' => 'Scraping already in progress',
                    'progress_id' => $existingProgress->id
                ]);
            }

            // Create progress record
            $progress = ScrapingProgress::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'progress' => 0,
                'message' => 'Job queued. Starting soon...'
            ]);

            ScrapeJobs::dispatch($user->id, $selectedRoles);
            Log::info("Background job search queued for user {$user->id}");

            return response()->json([
                'success' => true,
                'message' => 'Job scraping started',
                'progress_id' => $progress->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting scraping job: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to start scraping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current scraping progress
     */
    public function getProgress(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $progress = ScrapingProgress::where('user_id', $user->id)
                ->latest()
                ->first();

            if (!$progress) {
                return response()->json([
                    'status' => 'not_started',
                    'progress' => 0,
                    'message' => 'No scraping in progress'
                ]);
            }

            return response()->json([
                'status' => $progress->status,
                'progress' => $progress->progress,
                'message' => $progress->message,
                'total_jobs' => $progress->total_jobs,
                'completed_at' => $progress->completed_at
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting scraping progress: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if scraping is completed
     */
    public function isScrappingComplete(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $jobCount = JobMatch::where('user_id', $user->id)->count();

            return response()->json([
                'is_complete' => $jobCount > 0,
                'total_jobs' => $jobCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking scraping completion: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to check completion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check queue/cron job status
     */
    public function checkQueueStatus(Request $request)
    {
        try {
            $queueDriver = config('queue.default');
            $isEnabled = env('QUEUE_CONNECTION') !== null;

            return response()->json([
                'queue_enabled' => $isEnabled,
                'queue_driver' => $queueDriver,
                'status' => $isEnabled ? 'active' : 'inactive'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking queue status: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to check queue status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job matches
     */
    public function getJobMatches(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $jobs = JobMatch::where('user_id', $user->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'total_jobs' => $jobs->count(),
                'jobs' => $jobs
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting job matches: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get job matches: ' . $e->getMessage()
            ], 500);
        }
    }
}
