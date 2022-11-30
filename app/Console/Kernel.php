<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        $schedule->command('queue:work')->everyMinute();
        $schedule->command('class:cron')->everyMinute();
        $schedule->command('class:completed')->everyMinute();
        $schedule->command('assignment:completed')->dailyAt("00:01");
        $schedule->command('course:completed')->everyMinute();
        $schedule->command('course:reminder')->hourly();
        $schedule->command('assignment:deadline_alert')->dailyAt("00:01");
        $schedule->command('pre_class_hour:cron')->everyMinute();
        $schedule->command('pre_class_day:cron')->dailyAt("00:01");
        $schedule->command('payment:records')->everyWeek();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
