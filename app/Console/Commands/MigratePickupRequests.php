<?php

namespace App\Console\Commands;

use App\Data\Constants;
use App\Data\Models\File;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePickupRequests extends Command
{
    const ROWS_PER_ITERATION = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Constants::MIGRATE_PICKUP_REQUESTS_COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Pickup Requests from old DB schema to new.';

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
        $this->info('Performing the migration process...');

        DB::statement('ALTER TABLE `pickup_request` ADD COLUMN `equipment_list` VARCHAR(255) NULL;');

        $this->migrateEbay();
        $this->migrateGoDaddy();
        $this->migratePrincipal();
        $this->migrateWorkday();
        $this->migrateYahoo();

        $pickupRequests = DB::connection('mysql')->table('pickup_request')->get();

        foreach ($pickupRequests as $pickupRequest) {
            if ($pickupRequest->equipment_list) {
                $file = new File();

                $file->filename = $pickupRequest->equipment_list;
                $file->name = $pickupRequest->equipment_list;
                $file->url = 'NEEDS_UPDATE';
                $file->size = 0;
                $file->pickup_request_id = $pickupRequest->id;

                $file->save();
            }
        }

        DB::statement('ALTER TABLE `pickup_request` DROP COLUMN `equipment_list`;');

        $this->info('Done.');
    }

    private function migrateEbay()
    {
        $resultCheck = DB::connection('old')->table('ebaypickuprequests')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total records to migrate: ' . $totalNumberOfRecords . '...');

        $site = Site::where('code', '=', 'ebay')->first();

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('ebaypickuprequests')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $pickupRequestsToMigrateArray = [];

            foreach ($results as $pickupRequest) {
                array_push($pickupRequestsToMigrateArray, $this->translateEbayPickupRequest($pickupRequest, $site));
            }

            DB::connection('mysql')->table('pickup_request')->insert($pickupRequestsToMigrateArray);
        }
    }

    private function translateEbayPickupRequest($pickupRequest, $site) {
        $translatedPickupRequestArray = [
            'site_id' => $site->id,
            'company_name' => $pickupRequest->companyName,
            'contact_name' => $pickupRequest->contactName,
            'contact_phone_number' => $pickupRequest->contactPhoneNumber,
            'contact_address_1' => $pickupRequest->address1,
            'contact_address_2' => $pickupRequest->address2,
            'contact_city' => $pickupRequest->city,
            'contact_state' => $pickupRequest->state,
            'contact_zip' => $pickupRequest->zip,
            'contact_cell_number' => $pickupRequest->contactCellNumber,
            'contact_email_address' => $pickupRequest->contactEmailAddress,
            'reference_number' => $pickupRequest->amgmTicketNumber,
            'num_desktops' => $pickupRequest->numDesktops,
            'num_laptops' => $pickupRequest->numLapTops,
            'num_monitors' => $pickupRequest->numMonitors,
            'num_printers' => $pickupRequest->numPrinters,
            'num_servers' => $pickupRequest->numServers,
            'num_networking' => $pickupRequest->numNetworking,
            'num_storage_systems' => $pickupRequest->numStorageSystem,
            'num_ups' => $pickupRequest->numUPS,
            'num_racks' => $pickupRequest->numRacks,
            'num_other' => $pickupRequest->numOther,
            'num_misc' => $pickupRequest->numMisc,
            'total_num_assets' => $pickupRequest->totalNumAssets,
            'preferred_pickup_date' => ($pickupRequest->preferredPickupDateTime ? Carbon::createFromFormat('m/d/Y h:i A' , $pickupRequest->preferredPickupDateTime) : null),
            'units_located_near_dock' => ($pickupRequest->unitsLocatedNearDock ? ($pickupRequest->unitsLocatedNearDock == 'Yes' ? true : false ) : null),
            'units_on_single_floor' => ($pickupRequest->unitsOnSingleFloor ? ($pickupRequest->unitsOnSingleFloor == 'Yes' ? true : false ) : null),
            'is_lift_gate_needed' => ($pickupRequest->isLiftGateNeeded ? ($pickupRequest->isLiftGateNeeded == 'Yes' ? true : false ) : null),
            'is_loading_dock_present' => ($pickupRequest->isThereLoadingDock ? ($pickupRequest->isThereLoadingDock == 'Yes' ? true : false ) : null),
            'dock_appointment_required' => ($pickupRequest->dockApptRequired ? ($pickupRequest->dockApptRequired == 'Yes' ? true : false ) : null),
            'assets_need_packaging' => ($pickupRequest->assetsNeedPackaged ? ($pickupRequest->assetsNeedPackaged == 'Yes' ? true : false ) : null),
            'hardware_on_skids' => ($pickupRequest->hardwareOnSkids ? ($pickupRequest->hardwareOnSkids == 'Yes' ? true : false ) : null),
            'num_skids' => $pickupRequest->numOfSkids,
            'bm_company_name' => $pickupRequest->bmCompanyName,
            'bm_contact_name' => $pickupRequest->bmContactName,
            'bm_phone_number' => $pickupRequest->bmPhoneNumber,
            'bm_address_1' => $pickupRequest->bmAddress1,
            'bm_address_2' => $pickupRequest->bmAddress2,
            'bm_city' => $pickupRequest->bmCity,
            'bm_state' => $pickupRequest->bmState,
            'bm_zip' => $pickupRequest->bmZip,
            'bm_cell_number' => $pickupRequest->bmCellNumber,
            'special_instructions' => $pickupRequest->specialInstructions,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'equipment_list' => $pickupRequest->equipmentListFile
        ];

        if ($translatedPickupRequestArray['preferred_pickup_date']) {
            if ($translatedPickupRequestArray['preferred_pickup_date']->year < 1900) {
                $translatedPickupRequestArray['preferred_pickup_date'] = Carbon::createFromFormat('m/d/y h:i A' , $pickupRequest->preferredPickupDateTime);
            }
        }

        return $translatedPickupRequestArray;
    }

    private function migrateGoDaddy()
    {
        $resultCheck = DB::connection('old')->table('godaddypickuprequests')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total records to migrate: ' . $totalNumberOfRecords . '...');

        $site = Site::where('code', '=', 'godaddy')->first();

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('godaddypickuprequests')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $pickupRequestsToMigrateArray = [];

            foreach ($results as $pickupRequest) {
                array_push($pickupRequestsToMigrateArray, $this->translateGoDaddyPickupRequest($pickupRequest, $site));
            }

            DB::connection('mysql')->table('pickup_request')->insert($pickupRequestsToMigrateArray);
        }
    }

    private function translateGoDaddyPickupRequest($pickupRequest, $site) {
        $translatedPickupRequestArray = [
            'site_id' => $site->id,
            'company_name' => $pickupRequest->companyName,
            'contact_name' => $pickupRequest->contactName,
            'contact_phone_number' => $pickupRequest->contactPhoneNumber,
            'contact_address_1' => $pickupRequest->address1,
            'contact_address_2' => $pickupRequest->address2,
            'contact_city' => $pickupRequest->city,
            'contact_state' => $pickupRequest->state,
            'contact_zip' => $pickupRequest->zip,
            'contact_country' => $pickupRequest->country,
            'contact_cell_number' => $pickupRequest->contactCellNumber,
            'contact_email_address' => $pickupRequest->contactEmailAddress,
            'reference_number' => $pickupRequest->customerReferenceNumber,
            'num_desktops' => $pickupRequest->numDesktops,
            'num_laptops' => $pickupRequest->numLapTops,
            'num_monitors' => $pickupRequest->numMonitors,
            'num_printers' => $pickupRequest->numPrinters,
            'num_servers' => $pickupRequest->numServers,
            'num_networking' => $pickupRequest->numNetworking,
            'num_storage_systems' => $pickupRequest->numStorageSystem,
            'num_ups' => $pickupRequest->numUPS,
            'num_racks' => $pickupRequest->numRacks,
            'num_other' => $pickupRequest->numOther,
            'num_misc' => $pickupRequest->numMisc,
            'total_num_assets' => $pickupRequest->totalNumAssets,
            'preferred_pickup_date_information' => $pickupRequest->preferredPickupDateTime,
            'units_located_near_dock' => ($pickupRequest->unitsLocatedNearDock ? ($pickupRequest->unitsLocatedNearDock == 'Yes' ? true : false ) : null),
            'units_on_single_floor' => ($pickupRequest->unitsOnSingleFloor ? ($pickupRequest->unitsOnSingleFloor == 'Yes' ? true : false ) : null),
            'is_loading_dock_present' => ($pickupRequest->isThereLoadingDock ? ($pickupRequest->isThereLoadingDock == 'Yes' ? true : false ) : null),
            'dock_appointment_required' => ($pickupRequest->dockApptRequired ? ($pickupRequest->dockApptRequired == 'Yes' ? true : false ) : null),
            'assets_need_packaging' => ($pickupRequest->assetsNeedPackaged ? ($pickupRequest->assetsNeedPackaged == 'Yes' ? true : false ) : null),
            'hardware_on_skids' => ($pickupRequest->hardwareOnSkids ? ($pickupRequest->hardwareOnSkids == 'Yes' ? true : false ) : null),
            'num_skids' => $pickupRequest->numOfSkids,
            'bm_company_name' => $pickupRequest->bmCompanyName,
            'bm_contact_name' => $pickupRequest->bmContactName,
            'bm_phone_number' => $pickupRequest->bmPhoneNumber,
            'bm_address_1' => $pickupRequest->bmAddress1,
            'bm_address_2' => $pickupRequest->bmAddress2,
            'bm_city' => $pickupRequest->bmCity,
            'bm_state' => $pickupRequest->bmState,
            'bm_zip' => $pickupRequest->bmZip,
            'bm_country' => $pickupRequest->bmCountry,
            'bm_cell_number' => $pickupRequest->bmCellNumber,
            'special_instructions' => $pickupRequest->specialInstructions,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'equipment_list' => $pickupRequest->equipmentListFile
        ];

        return $translatedPickupRequestArray;
    }

    private function migratePrincipal()
    {
        $resultCheck = DB::connection('old')->table('principalpickuprequests')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total records to migrate: ' . $totalNumberOfRecords . '...');

        $site = Site::where('code', '=', 'principal')->first();

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('principalpickuprequests')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $pickupRequestsToMigrateArray = [];

            foreach ($results as $pickupRequest) {
                array_push($pickupRequestsToMigrateArray, $this->translatePrincipalPickupRequest($pickupRequest, $site));
            }

            DB::connection('mysql')->table('pickup_request')->insert($pickupRequestsToMigrateArray);
        }
    }

    private function translatePrincipalPickupRequest($pickupRequest, $site) {
        $translatedPickupRequestArray = [
            'site_id' => $site->id,
            'company_name' => $pickupRequest->companyName,
            'contact_name' => $pickupRequest->contactName,
            'contact_phone_number' => $pickupRequest->phoneNumber,
            'contact_address_1' => $pickupRequest->address1,
            'contact_address_2' => $pickupRequest->address2,
            'contact_city' => $pickupRequest->city,
            'contact_state' => $pickupRequest->state,
            'contact_zip' => $pickupRequest->zip,
            'contact_cell_number' => $pickupRequest->cellNumber,
            'contact_email_address' => $pickupRequest->emailAddress,
            'num_internal_hard_drives' => $pickupRequest->numInternalHardDrives,
            'num_desktops' => $pickupRequest->numDesktops,
            'num_laptops' => $pickupRequest->numLapTops,
            'num_monitors' => $pickupRequest->numMonitors,
            'num_printers' => $pickupRequest->numPrinters,
            'num_servers' => $pickupRequest->numServers,
            'num_networking' => $pickupRequest->numNetworking,
            'num_storage_systems' => $pickupRequest->numStorageSystem,
            'num_ups' => $pickupRequest->numUPS,
            'num_other' => $pickupRequest->numOther,
            'num_misc' => $pickupRequest->numMisc,
            'total_num_assets' => $pickupRequest->totalNumAssets,
            'internal_hard_drive_encrypted' => ($pickupRequest->internalHardDriveEncrypted ? ($pickupRequest->internalHardDriveEncrypted == 'Yes' ? true : false ) : null),
            'internal_hard_drive_wiped' => ($pickupRequest->internalHardDriveWiped ? ($pickupRequest->internalHardDriveWiped == 'Yes' ? true : false ) : null),
            'desktop_encrypted' => ($pickupRequest->desktopEncrypted ? ($pickupRequest->desktopEncrypted == 'Yes' ? true : false ) : null),
            'desktop_hard_drive_wiped' => ($pickupRequest->desktopHardDriveWiped ? ($pickupRequest->desktopHardDriveWiped == 'Yes' ? true : false ) : null),
            'laptop_encrypted' => ($pickupRequest->laptopEncrypted ? ($pickupRequest->laptopEncrypted == 'Yes' ? true : false ) : null),
            'laptop_hard_drive_wiped' => ($pickupRequest->laptopHardDriveWiped ? ($pickupRequest->laptopHardDriveWiped == 'Yes' ? true : false ) : null),
            'server_encrypted' => ($pickupRequest->serverEncrypted ? ($pickupRequest->serverEncrypted == 'Yes' ? true : false ) : null),
            'server_hard_drive_wiped' => ($pickupRequest->serverHardDriveWiped ? ($pickupRequest->serverHardDriveWiped == 'Yes' ? true : false ) : null),
            'preferred_pickup_date_information' => $pickupRequest->preferredPickupTime,
            'units_located_near_dock' => ($pickupRequest->unitsLocatedNearDock ? ($pickupRequest->unitsLocatedNearDock == 'Yes' ? true : false ) : null),
            'units_on_single_floor' => ($pickupRequest->unitsOnSingleFloor ? ($pickupRequest->unitsOnSingleFloor == 'Yes' ? true : false ) : null),
            'is_loading_dock_present' => ($pickupRequest->isThereLoadingDock ? ($pickupRequest->isThereLoadingDock == 'Yes' ? true : false ) : null),
            'dock_appointment_required' => ($pickupRequest->dockApptRequired ? ($pickupRequest->dockApptRequired == 'Yes' ? true : false ) : null),
            'assets_need_packaging' => ($pickupRequest->assetsNeedPackaged ? ($pickupRequest->assetsNeedPackaged == 'Yes' ? true : false ) : null),
            'bm_company_name' => $pickupRequest->bmCompanyName,
            'bm_contact_name' => $pickupRequest->bmContactName,
            'bm_phone_number' => $pickupRequest->bmPhoneNumber,
            'bm_address_1' => $pickupRequest->bmAddress1,
            'bm_address_2' => $pickupRequest->bmAddress2,
            'bm_city' => $pickupRequest->bmCity,
            'bm_state' => $pickupRequest->bmState,
            'bm_zip' => $pickupRequest->bmZip,
            'bm_cell_number' => $pickupRequest->bmCellNumber,
            'special_instructions' => $pickupRequest->specialInstructions,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'equipment_list' => $pickupRequest->equipmentListFile
        ];

        return $translatedPickupRequestArray;
    }

    private function migrateWorkday()
    {
        $resultCheck = DB::connection('old')->table('workdaypickuprequests')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total records to migrate: ' . $totalNumberOfRecords . '...');

        $site = Site::where('code', '=', 'workday')->first();

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('workdaypickuprequests')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $pickupRequestsToMigrateArray = [];

            foreach ($results as $pickupRequest) {
                array_push($pickupRequestsToMigrateArray, $this->translateWorkdayPickupRequest($pickupRequest, $site));
            }

            DB::connection('mysql')->table('pickup_request')->insert($pickupRequestsToMigrateArray);
        }
    }

    private function translateWorkdayPickupRequest($pickupRequest, $site) {
        $translatedPickupRequestArray = [
            'site_id' => $site->id,
            'company_name' => $pickupRequest->companyName,
            'company_division' => $pickupRequest->workdayDivision,
            'contact_name' => $pickupRequest->contactName,
            'contact_phone_number' => $pickupRequest->phoneNumber,
            'contact_address_1' => $pickupRequest->address1,
            'contact_address_2' => $pickupRequest->address2,
            'contact_city' => $pickupRequest->city,
            'contact_state' => $pickupRequest->state,
            'contact_zip' => $pickupRequest->zip,
            'contact_country' => $pickupRequest->country,
            'contact_cell_number' => $pickupRequest->cellNumber,
            'contact_email_address' => $pickupRequest->emailAddress,
            'reference_number' => $pickupRequest->customerReferenceNumber,
            'num_desktops' => $pickupRequest->numDesktops,
            'num_laptops' => $pickupRequest->numLapTops,
            'num_monitors' => $pickupRequest->numMonitors,
            'num_printers' => $pickupRequest->numPrinters,
            'num_servers' => $pickupRequest->numServers,
            'num_networking' => $pickupRequest->numNetworking,
            'num_storage_systems' => $pickupRequest->numStorageSystem,
            'num_ups' => $pickupRequest->numUPS,
            'num_racks' => $pickupRequest->numRacks,
            'num_other' => $pickupRequest->numOther,
            'num_misc' => $pickupRequest->numMisc,
            'total_num_assets' => $pickupRequest->totalNumAssets,
            'preferred_pickup_date_information' => $pickupRequest->preferredPickupTime,
            'units_located_near_dock' => ($pickupRequest->unitsLocatedNearDock ? ($pickupRequest->unitsLocatedNearDock == 'Yes' ? true : false ) : null),
            'units_on_single_floor' => ($pickupRequest->unitsOnSingleFloor ? ($pickupRequest->unitsOnSingleFloor == 'Yes' ? true : false ) : null),
            'is_loading_dock_present' => ($pickupRequest->isThereLoadingDock ? ($pickupRequest->isThereLoadingDock == 'Yes' ? true : false ) : null),
            'dock_appointment_required' => ($pickupRequest->dockApptRequired ? ($pickupRequest->dockApptRequired == 'Yes' ? true : false ) : null),
            'assets_need_packaging' => ($pickupRequest->assetsNeedPackaged ? ($pickupRequest->assetsNeedPackaged == 'Yes' ? true : false ) : null),
            'hardware_on_skids' => ($pickupRequest->hardwareOnSkids ? ($pickupRequest->hardwareOnSkids == 'Yes' ? true : false ) : null),
            'num_skids' => $pickupRequest->numOfSkids,
            'bm_company_name' => $pickupRequest->bmCompanyName,
            'bm_contact_name' => $pickupRequest->bmContactName,
            'bm_phone_number' => $pickupRequest->bmPhoneNumber,
            'bm_address_1' => $pickupRequest->bmAddress1,
            'bm_address_2' => $pickupRequest->bmAddress2,
            'bm_city' => $pickupRequest->bmCity,
            'bm_state' => $pickupRequest->bmState,
            'bm_zip' => $pickupRequest->bmZip,
            'bm_country' => $pickupRequest->bmCountry,
            'bm_cell_number' => $pickupRequest->bmCellNumber,
            'special_instructions' => $pickupRequest->specialInstructions,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'equipment_list' => $pickupRequest->equipmentListFile
        ];

        return $translatedPickupRequestArray;
    }

    private function migrateYahoo()
    {
        $resultCheck = DB::connection('old')->table('yahoopickuprequests')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total records to migrate: ' . $totalNumberOfRecords . '...');

        $site = Site::where('code', '=', 'yahoo')->first();

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('yahoopickuprequests')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $pickupRequestsToMigrateArray = [];

            foreach ($results as $pickupRequest) {
                array_push($pickupRequestsToMigrateArray, $this->translateYahooPickupRequest($pickupRequest, $site));
            }

            DB::connection('mysql')->table('pickup_request')->insert($pickupRequestsToMigrateArray);
        }
    }

    private function translateYahooPickupRequest($pickupRequest, $site) {
        $translatedPickupRequestArray = [
            'site_id' => $site->id,
            'company_name' => $pickupRequest->companyName,
            'company_division' => $pickupRequest->yahooDivision,
            'contact_name' => $pickupRequest->contactName,
            'contact_phone_number' => $pickupRequest->phoneNumber,
            'contact_address_1' => $pickupRequest->address1,
            'contact_address_2' => $pickupRequest->address2,
            'contact_city' => $pickupRequest->city,
            'contact_state' => $pickupRequest->state,
            'contact_zip' => $pickupRequest->zip,
            'contact_country' => $pickupRequest->country,
            'contact_cell_number' => $pickupRequest->cellNumber,
            'contact_email_address' => $pickupRequest->emailAddress,
            'reference_number' => $pickupRequest->customerReferenceNumber,
            'num_desktops' => $pickupRequest->numDesktops,
            'num_laptops' => $pickupRequest->numLapTops,
            'num_monitors' => $pickupRequest->numMonitors,
            'num_printers' => $pickupRequest->numPrinters,
            'num_servers' => $pickupRequest->numServers,
            'num_networking' => $pickupRequest->numNetworking,
            'num_storage_systems' => $pickupRequest->numStorageSystem,
            'num_ups' => $pickupRequest->numUPS,
            'num_racks' => $pickupRequest->numRacks,
            'num_other' => $pickupRequest->numOther,
            'num_misc' => $pickupRequest->numMisc,
            'total_num_assets' => $pickupRequest->totalNumAssets,
            'preferred_pickup_date_information' => $pickupRequest->preferredPickupTime,
            'units_located_near_dock' => ($pickupRequest->unitsLocatedNearDock ? ($pickupRequest->unitsLocatedNearDock == 'Yes' ? true : false ) : null),
            'units_on_single_floor' => ($pickupRequest->unitsOnSingleFloor ? ($pickupRequest->unitsOnSingleFloor == 'Yes' ? true : false ) : null),
            'is_loading_dock_present' => ($pickupRequest->isThereLoadingDock ? ($pickupRequest->isThereLoadingDock == 'Yes' ? true : false ) : null),
            'dock_appointment_required' => ($pickupRequest->dockApptRequired ? ($pickupRequest->dockApptRequired == 'Yes' ? true : false ) : null),
            'assets_need_packaging' => ($pickupRequest->assetsNeedPackaged ? ($pickupRequest->assetsNeedPackaged == 'Yes' ? true : false ) : null),
            'hardware_on_skids' => ($pickupRequest->hardwareOnSkids ? ($pickupRequest->hardwareOnSkids == 'Yes' ? true : false ) : null),
            'num_skids' => $pickupRequest->numOfSkids,
            'bm_company_name' => $pickupRequest->bmCompanyName,
            'bm_contact_name' => $pickupRequest->bmContactName,
            'bm_phone_number' => $pickupRequest->bmPhoneNumber,
            'bm_address_1' => $pickupRequest->bmAddress1,
            'bm_address_2' => $pickupRequest->bmAddress2,
            'bm_city' => $pickupRequest->bmCity,
            'bm_state' => $pickupRequest->bmState,
            'bm_zip' => $pickupRequest->bmZip,
            'bm_country' => $pickupRequest->bmCountry,
            'bm_cell_number' => $pickupRequest->bmCellNumber,
            'special_instructions' => $pickupRequest->specialInstructions,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'equipment_list' => $pickupRequest->equipmentListFile
        ];

        return $translatedPickupRequestArray;
    }
}
