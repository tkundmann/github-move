<?php

namespace App\Console\Commands;

use App\Jobs\PruneAssetsJob;
use App\Data\Constants;
use Illuminate\Console\Command;

class PruneAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Constants::PRUNE_ASSETS_COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes Assets for which \'manufacturerSerialNum\' starts with VOID and \'productFamily\' equals SKID.';

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
        $this->info('Pruning Assets...');
        $job = new PruneAssetsJob();
        $job->handle();
        $this->info('Done. See laravel.log for details.');
    }
}
