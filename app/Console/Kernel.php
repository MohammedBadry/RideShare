<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CacheAvailableDrivers;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cache available drivers every minute for high-frequency access
        $schedule->job(new CacheAvailableDrivers)->everyMinute();
        
        // Alternative: Run every 30 seconds for even more frequent updates
        // $schedule->job(new CacheAvailableDrivers)->everyThirtySeconds();
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