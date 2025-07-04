<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Archive old job posts daily at 2 AM
        $schedule->command('jobs:archive-old')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Remove unverified users weekly on Sundays at 3 AM
        $schedule->command('users:remove-unverified')
            ->weeklyOn(0, '03:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Process job matching every 6 hours
        $schedule->command('jobs:match-candidates')
            ->everySixHours()
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
