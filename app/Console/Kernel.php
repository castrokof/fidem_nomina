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
        // Sincronizar agenda de citas cada 30 minutos
        $schedule->command('agenda:sincronizar')
                 ->everyThirtyMinutes()
                 ->withoutOverlapping();

        // Actualizar llegadas cada 10 minutos durante horario laboral
        $schedule->command('agenda:actualizar-llegadas')
                 ->everyTenMinutes()
                 ->between('7:00', '19:00')
                 ->withoutOverlapping();
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
