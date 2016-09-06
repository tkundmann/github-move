<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Data\Models\Asset;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PruneAssetsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Assets prune...');

        $assetsToDelete = Asset::where('manufacturerSerialNum', 'like', 'VOID%')->get();

        if (count($assetsToDelete) > 0) {
            Log::info('Assets to delete: ' . $assetsToDelete->toJson());
            $deletedCount = Asset::where('manufacturerSerialNum', 'like', 'VOID%')->delete();
            Log::info('Assets deleted (' . $deletedCount . ')');
        }
        else {
            Log::info('No Assets to delete.');
        }
    }
}
