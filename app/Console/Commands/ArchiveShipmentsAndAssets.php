<?php

namespace App\Console\Commands;

use App\Jobs\ArchiveShipmentsAndAssetsJob;
use App\Data\Constants;
use Illuminate\Console\Command;

class ArchiveShipmentsAndAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Constants::ARCHIVE_SHIPMENTS_ASSETS_COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archives (moves to \'*_archive\' table) Shipments and Assets that have the \'updateDateTime\' older than 5 years';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Performing the archiving process...');
        $job = new ArchiveShipmentsAndAssetsJob();
        $job->handle();
        $this->info('Done. See laravel.log for details.');
    }
}
