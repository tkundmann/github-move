<?php

namespace App\Jobs;

use App\Data\Models\TrackingNumber;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveTrackingNumbersJob extends Job implements ShouldQueue
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
        $archiveDateCutPoint = Carbon::now();
        $archiveDateCutPoint->subDays(120);

        Log::info('Tracking Numbers archive...');

        $trackingNumbersToArchive = TrackingNumber::where('updateDateTime', '<', $archiveDateCutPoint)->limit(1000)->get();
        if (count($trackingNumbersToArchive) > 0) {

            $trackingNumbersToArchiveArray = $trackingNumbersToArchive->toArray();
            DB::table('tracking_number_archive')->insert($trackingNumbersToArchiveArray);

            foreach ($trackingNumbersToArchive as $trackingNumberToArchive) {
                $trackingNumberToArchive->delete();
            }

            Log::info('Tracking Numbers archived.');
        }
        else {
            Log::info('No Tracking Numbers to archive.');
        }
    }
}
