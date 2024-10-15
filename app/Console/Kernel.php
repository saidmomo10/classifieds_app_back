<?php

namespace App\Console;

use App\Jobs\UpdateSubscriptionStatus;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new UpdateSubscriptionStatus)->everySecond();    
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
