<?php

namespace App\Console;

use App\Console\Commands\AfterSalesMessageCron;
use App\Console\Commands\QuestionAnsweredCron;
use App\Console\Commands\QuestionCron;
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
        QuestionCron::class,
        QuestionAnsweredCron::class,
        AfterSalesMessageCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('questions:load')->everyFifteenMinutes();
        $schedule->command('questions:answered')->hourly();
        $schedule->command('aftersalesmessage:send')->everyFifteenMinutes();
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
