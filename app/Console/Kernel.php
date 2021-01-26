<?php

namespace App\Console;

use App\Console\Commands\CacheRefresher;
use App\Console\Commands\ClearTempSearch;
use App\Console\Commands\FixProductUrls;
use App\Console\Commands\PriceUpdater;
use App\Console\Commands\ProductImporterCommand;
use App\Console\Commands\TraitMakeCommand;
use App\Console\Commands\UpdateCategorySlugs;
use App\Console\Commands\UpdateProductTotal;
use App\Traits\Environment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use Environment;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        TraitMakeCommand::class,
        UpdateProductTotal::class,
        ClearTempSearch::class,
        FixProductUrls::class,
        PriceUpdater::class,
        CacheRefresher::class,
        ProductImporterCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('php artisan datafiniti:clear-search')->weekly();
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
