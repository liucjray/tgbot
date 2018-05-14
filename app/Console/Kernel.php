<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // * * * * * php /var/www/tgbot/artisan schedule:run >> /dev/null 2>&1

        $schedule->command('command:telegram:notification')->hourly();

        $schedule->command('command:telegram:notification:oa')->everyMinute();

        $schedule->command('command:telegram:notification:stock')->everyMinute();

        $schedule->command('command:telegram:notification:japanese:50')->everyFiveMinutes();

        $schedule->command('command:telegram:notification:english')->everyFiveMinutes();
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
