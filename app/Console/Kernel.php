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
        // $schedule->command('inspire')->hourly();
         // Clear routes cache every day at midnight
        $schedule->command('route:cache')->dailyAt('0:00');

        // Clear config cache every day at midnight
        $schedule->command('config:cache')->dailyAt('0:00');

        // Clear view cache every day at midnight
        $schedule->command('view:clear')->dailyAt('0:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}