<?php

namespace App\Jobs;

use App\Data\Models\Asset;
use App\Data\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveShipmentsAndAssetsJob extends Job implements ShouldQueue
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
        $archiveDateCutPoint->subYears(5);

        Log::info('Shipments/Assets archive...');

        $shipmentsToArchive = Shipment::where('updateDateTime', '<', $archiveDateCutPoint)->get();
        if (count($shipmentsToArchive) > 0) {
            Log::info('Shipments to archive: ' . $shipmentsToArchive->toJson());

            $shipmentsToArchiveArray = $shipmentsToArchive->toArray();
            DB::table('shipment_archive')->insert($shipmentsToArchiveArray);

            foreach ($shipmentsToArchive as $shipmentToArchive) {
                $shipmentToArchive->delete();
            }

            Log::info('Shipments archived.');
        }
        else {
            Log::info('No Shipments to archive.');
        }

        $assetsToArchive = Asset::where('updateDateTime', '<', $archiveDateCutPoint)->get();
        if (count($assetsToArchive) > 0) {
            Log::info('Assets to archive: ' . $assetsToArchive->toJson());

            $assetsToArchiveArray = $assetsToArchive->toArray();
            DB::table('asset_archive')->insert($assetsToArchiveArray);

            foreach ($assetsToArchive as $assetToArchive) {
                $assetToArchive->delete();
            }

            Log::info('Assets archived.');
        }
        else {
            Log::info('No Assets to archive.');
        }
    }
}
