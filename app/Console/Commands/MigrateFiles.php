<?php

namespace App\Console\Commands;

use App\Data\Constants;
use App\Data\Models\File;
use App\Data\Models\Page;
use App\Data\Models\Shipment;
use App\Data\Models\Site;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Constants::MIGRATE_FILES_COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Settlements and Certificates files stored in Amazon S3, creating DB records for them.';

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
        $allSiteDirectories = Storage::cloud()->directories(Constants::UPLOAD_DIRECTORY);

        foreach($allSiteDirectories as $siteDirectory) {
            $siteName = substr($siteDirectory, strrpos($siteDirectory, '/') + 1);

            $site = Site::where('code', $siteName)->first();

            if ($site) {
                $siteSubdirectories = Storage::cloud()->directories($siteDirectory);

                foreach ($siteSubdirectories as $siteSubdirectory) {
                    if (ends_with($siteSubdirectory, 'certificate_of_data_wipe')) {
                        $this->migrateCertificatesOfDataWipe($siteSubdirectory, $site);
                    } else if (ends_with($siteSubdirectory, 'certificate_of_destruction')) {
                        $this->migrateCertificatesOfDestruction($siteSubdirectory, $site);
                    } else if (ends_with($siteSubdirectory, 'settlement')) {
                        $this->migrateSettlements($siteSubdirectory, $site);
                    }
                }
            }
        }
    }

    private function migrateCertificatesOfDataWipe($directory, $site) {
        $targetPage = null;
        $existingPage = $site->pages->where('type', 'Certificates of Data Wipe')->first();

        if (!$existingPage) {
            $newPage = new Page();
            $newPage->type = 'Certificates of Data Wipe';
            $newPage->name = 'Certificates of Data Wipe';
            $newPage->siteId = $site->id;
            $newPage->save();

            $targetPage = $newPage;
        }
        else {
            $targetPage = $existingPage;
        }

        $files = Storage::cloud()->files($directory);

        foreach($files as $file) {
            $filename = substr($file, strrpos($file, '/') + 1);
            $url = Storage::cloud()->url($file);
            $size = Storage::cloud()->size($file);

            if (starts_with($filename, 'DATA')) {
                $withoutExtension = substr($filename, 0, strrpos($filename, '.'));
                $shipmentLotNumber = str_replace('DATA', null, $withoutExtension);

                if ($shipmentLotNumber) {
                    $shipment = Shipment::where('lot_number', $shipmentLotNumber)->first();

                    if ($shipment) {
                        $newFile = new File();

                        $newFile->filename = $filename;
                        $newFile->name = $filename;
                        $newFile->url = $url;
                        $newFile->size = $size;
                        $newFile->pageId = $targetPage->id;
                        $newFile->shipmentId = $shipment->id;

                        $newFile->save();
                    }
                }
            }
        }
    }

    private function migrateCertificatesOfDestruction($directory, $site) {
        $targetPage = null;
        $existingPage = $site->pages->where('type', 'Certificates of Recycling')->first();

        if (!$existingPage) {
            $newPage = new Page();
            $newPage->type = 'Certificates of Recycling';
            $newPage->name = 'Certificates of Recycling';
            $newPage->siteId = $site->id;
            $newPage->save();

            $targetPage = $newPage;
        }
        else {
            $targetPage = $existingPage;
        }

        $files = Storage::cloud()->files($directory);

        foreach($files as $file) {
            $filename = substr($file, strrpos($file, '/') + 1);
            $url = Storage::cloud()->url($file);
            $size = Storage::cloud()->size($file);

            if (starts_with($filename, 'DEST')) {
                $withoutExtension = substr($filename, 0, strrpos($filename, '.'));
                $shipmentLotNumber = str_replace('DEST', null, $withoutExtension);

                if ($shipmentLotNumber) {
                    $shipment = Shipment::where('lot_number', $shipmentLotNumber)->first();

                    if ($shipment) {
                        $newFile = new File();

                        $newFile->filename = $filename;
                        $newFile->name = $filename;
                        $newFile->url = $url;
                        $newFile->size = $size;
                        $newFile->pageId = $targetPage->id;
                        $newFile->shipmentId = $shipment->id;

                        $newFile->save();
                    }
                }
            }
        }
    }

    private function migrateSettlements($directory, $site) {
        $targetPage = null;
        $existingPage = $site->pages->where('type', 'Settlements')->first();

        if (!$existingPage) {
            $newPage = new Page();
            $newPage->type = 'Settlements';
            $newPage->name = 'Settlements';
            $newPage->siteId = $site->id;
            $newPage->save();

            $targetPage = $newPage;
        }
        else {
            $targetPage = $existingPage;
        }

        $files = Storage::cloud()->files($directory);

        foreach($files as $file) {
            $filename = substr($file, strrpos($file, '/') + 1);
            $url = Storage::cloud()->url($file);
            $size = Storage::cloud()->size($file);

            if (starts_with($filename, 'settlement')) {
                $withoutExtension = substr($filename, 0, strrpos($filename, '.'));
                $shipmentLotNumber = str_replace('settlement', null, $withoutExtension);

                if ($shipmentLotNumber) {
                    $shipment = Shipment::where('lot_number', $shipmentLotNumber)->first();

                    if ($shipment) {
                        $newFile = new File();

                        $newFile->filename = $filename;
                        $newFile->name = $filename;
                        $newFile->url = $url;
                        $newFile->size = $size;
                        $newFile->pageId = $targetPage->id;
                        $newFile->shipmentId = $shipment->id;

                        $newFile->save();
                    }
                }
            }
        }
    }

}
