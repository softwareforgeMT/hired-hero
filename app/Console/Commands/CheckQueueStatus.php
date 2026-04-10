<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScrapingProgress;
use Illuminate\Support\Facades\DB;

class CheckQueueStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of the queue system and recent scraping tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Queue System Status Check ===\n');

        // 1. Check Queue Configuration
        $queueDriver = config('queue.default');
        $this->line("Queue Driver: <fg=cyan>{$queueDriver}</>");

        if ($queueDriver === 'database') {
            $this->checkDatabaseQueue();
        } elseif ($queueDriver === 'redis') {
            $this->checkRedisQueue();
        } elseif ($queueDriver === 'sync') {
            $this->warn('Warning: Using SYNC queue driver (jobs execute immediately)');
            $this->line('For production, use database or redis driver');
        }

        // 2. Check Recent Scraping Tasks
        $this->line("\n=== Recent Scraping Tasks ===\n");
        $this->checkRecentTasks();

        // 3. Check Jobs Table
        if ($queueDriver === 'database') {
            $this->line("\n=== Database Queue Table ===\n");
            $this->checkJobsTable();
        }

        $this->info('\n✓ Status check completed');
    }

    private function checkDatabaseQueue()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            $this->line('Database Connection: <fg=green>✓ Connected</>');

            // Check jobs table exists
            if (DB::getSchemaBuilder()->hasTable('jobs')) {
                $this->line('Jobs Table: <fg=green>✓ Exists</>');
                $pendingCount = DB::table('jobs')->count();
                $this->line("Pending Jobs: <fg=yellow>{$pendingCount}</>");
            } else {
                $this->error('Jobs Table: ✗ Missing (run: php artisan migrate)');
            }

            // Check failed_jobs table
            if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
                $failedCount = DB::table('failed_jobs')->count();
                if ($failedCount > 0) {
                    $this->warn("Failed Jobs: {$failedCount}");
                } else {
                    $this->line('Failed Jobs: <fg=green>✓ None</>');
                }
            }

        } catch (\Exception $e) {
            $this->error('Database Connection: ✗ Error - ' . $e->getMessage());
        }
    }

    private function checkRedisQueue()
    {
        try {
            $redis = \Redis::connection();
            $redis->ping();
            $this->line('Redis Connection: <fg=green>✓ Connected</>');
            
            $queueKey = 'queues:default';
            $length = $redis->llen($queueKey);
            $this->line("Jobs in Queue: <fg=yellow>{$length}</>");

        } catch (\Exception $e) {
            $this->error('Redis Connection: ✗ Error - ' . $e->getMessage());
        }
    }

    private function checkRecentTasks()
    {
        try {
            $recentTasks = ScrapingProgress::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            if ($recentTasks->isEmpty()) {
                $this->line('No recent scraping tasks found');
                return;
            }

            foreach ($recentTasks as $task) {
                $statusColor = match ($task->status) {
                    'completed' => 'green',
                    'processing' => 'yellow',
                    'pending' => 'blue',
                    'failed' => 'red',
                    default => 'white'
                };

                $this->line("User #{$task->user_id}:");
                $this->line("  Status: <fg={$statusColor}>{$task->status}</>");
                $this->line("  Progress: {$task->progress}%");
                $this->line("  Jobs Found: {$task->total_jobs}");
                $this->line("  Message: {$task->message}");
                $this->line("  Created: {$task->created_at->diffForHumans()}");
                $this->line("");
            }

        } catch (\Exception $e) {
            $this->error('Error checking recent tasks: ' . $e->getMessage());
        }
    }

    private function checkJobsTable()
    {
        try {
            $totalJobs = DB::table('jobs')->count();
            $this->line("Total Queued Jobs: {$totalJobs}");

            if ($totalJobs > 0) {
                $this->warn("\nNote: Jobs are waiting to be processed.");
                $this->line("Solution: Run <fg=cyan>php artisan queue:work</> to process them");
            }

            $recentJobs = DB::table('jobs')
                ->latest('created_at')
                ->limit(3)
                ->get(['id', 'queue', 'created_at']);

            if ($recentJobs->isNotEmpty()) {
                $this->line("\nRecent Jobs:");
                foreach ($recentJobs as $job) {
                    $this->line("  - ID: {$job->id}, Queue: {$job->queue}, Created: {$job->created_at}");
                }
            }

        } catch (\Exception $e) {
            $this->error('Error checking jobs table: ' . $e->getMessage());
        }
    }
}
