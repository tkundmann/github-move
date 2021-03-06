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
        Commands\Inspire::class,
        Commands\ArchiveShipmentsAndAssets::class,
        Commands\MigrateShipmentsAndAssets::class,
        Commands\MigratePickupRequests::class,
        Commands\MigrateFiles::class,
        Commands\PruneAssets::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:archive-shipments-assets')->hourly();
    }
}
