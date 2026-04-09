<?php

namespace App\Console\Commands;

use App\Jobs\SendWeeklyJobMatches;
use Illuminate\Console\Command;

class SendWeeklyJobMatchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job-matches:send-weekly {--user_id= : Send to specific user ID only}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send weekly job matches emails to all users (or specific user if --user_id provided)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Weekly Job Matches Send...');

        try {
            SendWeeklyJobMatches::dispatch();
            $this->info('✅ Weekly Job Matches Job Dispatched Successfully!');
            $this->line('The job will process in the queue.');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
