<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SendApplicationReminderEmail;
use App\Jobs\SendPlacementSurveyEmail;
use App\Jobs\HandleFreeAccessExpiration;
use App\Jobs\SendWeeklyJobMatches;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        $schedule->command('queue:work --stop-when-empty')
             ->everyMinute();

        // Send application reminder emails every day at 10 AM
        $schedule->job(new SendApplicationReminderEmail())
            ->daily()
            ->at('10:00');

        // Send placement survey emails every day at 2 PM
        $schedule->job(new SendPlacementSurveyEmail())
            ->daily()
            ->at('14:00');

        // Handle free access expiration every day at 3 PM
        $schedule->job(new HandleFreeAccessExpiration())
            ->daily()
            ->at('15:00');

        // Send weekly job matches every Monday at 9 AM
        $schedule->job(new SendWeeklyJobMatches())
            ->weeklyOn(1, '09:00') // 1 = Monday
            ->timezone('UTC');

        // Automated weekly portal stress test
        $schedule->command('portal:stress-test')
            ->weeklyOn(
                (int) config('stress-test.schedule_day', 0),
                (string) config('stress-test.schedule_time', '03:00')
            )
            ->timezone((string) config('stress-test.schedule_timezone', 'UTC'))
            ->withoutOverlapping()
            ->when(function () {
                return (bool) config('stress-test.enabled', true);
            });

        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
