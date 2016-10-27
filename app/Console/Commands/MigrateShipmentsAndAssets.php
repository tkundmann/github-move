<?php

namespace App\Console\Commands;

use App\Data\Constants;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateShipmentsAndAssets extends Command
{
    const ROWS_PER_ITERATION = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Constants::MIGRATE_SHIPMENTS_ASSETS_COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Shipments and Assets data (as well as archives) from old DB schema to new.';

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

        $this->migrateShipments();
        $this->migrateShipmentArchives();
        $this->migrateAssets();
        $this->migrateAssetArchives();

        $this->info('Done.');
    }

    private function migrateShipments()
    {
        $this->info('Shipments...');

        $resultCheck = DB::connection('old')->table('all_shipments')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total Shipments to migrate: ' . $totalNumberOfRecords . '...');

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('all_shipments')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $shipmentsToMigrateArray = [];

            foreach ($results as $shipment) {
                array_push($shipmentsToMigrateArray, $this->translateShipment($shipment));
            }

            DB::connection('mysql')->table('shipment')->insert($shipmentsToMigrateArray);
        }
    }

    private function migrateShipmentArchives()
    {
        $this->info('Shipment Archive...');

        $resultCheck = DB::connection('old')->table('all_shipments_archive')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total Shipment Archive records to migrate: ' . $totalNumberOfRecords . '...');

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('all_shipments_archive')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $shipmentsToMigrateArray = [];

            foreach ($results as $shipment) {
                array_push($shipmentsToMigrateArray, $this->translateShipment($shipment));
            }

            DB::connection('mysql')->table('shipment_archive')->insert($shipmentsToMigrateArray);
        }
    }

    private function migrateAssets()
    {
        $this->info('Assets...');

        $resultCheck = DB::connection('old')->table('all_assets')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total Assets to migrate: ' . $totalNumberOfRecords . '...');

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('all_assets')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $assetsToMigrateArray = [];

            foreach ($results as $asset) {
                array_push($assetsToMigrateArray, $this->translateAsset($asset));
            }

            DB::connection('mysql')->table('asset')->insert($assetsToMigrateArray);
        }
    }

    private function migrateAssetArchives()
    {
        $this->info('Asset Archive...');

        $resultCheck = DB::connection('old')->table('all_assets_archive')->paginate(self::ROWS_PER_ITERATION);

        $totalNumberOfRecords = $resultCheck->total();
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        $this->info('Total Asset Archive records to migrate: ' . $totalNumberOfRecords . '...');

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $paginator = DB::connection('old')->table('all_assets_archive')->paginate(self::ROWS_PER_ITERATION, ['*'], 'page', $i);
            $results = $paginator->items();

            $assetsToMigrateArray = [];

            foreach ($results as $asset) {
                array_push($assetsToMigrateArray, $this->translateAsset($asset));
            }

            DB::connection('mysql')->table('asset_archive')->insert($assetsToMigrateArray);
        }
    }

    private function translateShipment($shipment) {
        $translatedShipmentArray = [
            'lot_date' => $shipment->LotDate,
            'lot_approved_date' => $shipment->LotApprovedDate,
            'lot_number' => $shipment->LotNumber,
            'po_number' => $shipment->PONumber,
            'vendor_shipment_number' => $shipment->VendorShipmentNumber,
            'cost_center' => $shipment->CostCenter,
            'site_coordinator' => $shipment->SiteCoordinator,
            'vendor' => $shipment->Vendor,
            'vendor_client' => $shipment->VendorClient,
            'bill_of_lading' => $shipment->BillOfLading,
            'city_of_origin' => $shipment->CityOfOrigin,
            'schedule_pickup_date' => $shipment->SchedulePickupDate,
            'freight_carrier' => $shipment->FreightCarrier,
            'freight_invoice_number' => $shipment->FreightInvoiceNumber,
            'freight_charge' => $shipment->FreightCharge,
            'pickup_request_date' => $shipment->PickupRequestDate,
            'pickup_address' => $shipment->PickupAddress,
            'pickup_address_2' => $shipment->PickupAddress2,
            'pickup_city' => $shipment->PickupCity,
            'pickup_state' => $shipment->PickupState,
            'pickup_zip_code' => $shipment->PickupZipCode,
            'actual_pickup_date' => $shipment->ActualPickupDate,
            'date_received' => $shipment->DateReceived,
            'nf_received_date' => $shipment->NFReceivedDate,
            'nota_fiscal_transfer' => $shipment->NotaFiscalTransfer,
            'nota_fiscal_transfer_2' => $shipment->NotaFiscalTransfer2,
            'nota_fiscal_transfer_3' => $shipment->NotaFiscalTransfer3,
            'nota_fiscal_transfer_4' => $shipment->NotaFiscalTransfer4,
            'nota_fiscal_transfer_5' => $shipment->NotaFiscalTransfer5,
            'equipment_summary' => $shipment->EquipmentSummary,
            'total_weight_received' => $shipment->TotalWeightReceived,
            'number_of_skids' => $shipment->NumberOfSkids,
            'number_of_pieces' => $shipment->NumberOfPieces,
            'pre_audit_approved' => $shipment->PreAuditApproved,
            'audit_completed' => $shipment->AuditCompleted,
            'cert_of_data_wipe_num' => $shipment->CertOfDataWipeNum,
            'cert_of_destruction_num' => $shipment->CertOfDestructionNum,
            'import_date_time' => $shipment->ImportDateTime,
            'update_date_time' => $shipment->UpdateDateTime,
        ];

        if (!$translatedShipmentArray['import_date_time']) {
          if ($translatedShipmentArray['update_date_time']) {
              $translatedShipmentArray['import_date_time'] = $translatedShipmentArray['update_date_time'];
          }
          else {
              $translatedShipmentArray['import_date_time'] = Carbon::now();
          }
        }

        if (!$translatedShipmentArray['update_date_time']) {
            $translatedShipmentArray['update_date_time'] = Carbon::now();
        }

        return $translatedShipmentArray;
    }

    private function translateAsset($asset) {
        $translatedAssetArray = [
            'lot_date' => $asset->LotDate,
            'lot_number' => $asset->LotNumber,
            'bill_of_lading' => $asset->BillOfLading,
            'carrier' => $asset->Carrier,
            'po_number' => $asset->PONumber,
            'vendor_order_number' => $asset->VendorOrderNumber,
            'vendor' => $asset->Vendor,
            'vendor_client' => $asset->VendorClient,
            'date_arrived' => $asset->DateArrived,
            'shipment_date' => $asset->ShipmentDate,
            'barcode_number' => $asset->BarcodeNumber,
            'product_family' => $asset->ProductFamily,
            'manufacturer' => $asset->Manufacturer,
            'manufacturer_model_num' => $asset->ManufacturerModelNum,
            'manufacturer_part_num' => $asset->ManufacturerPartNum,
            'manufacturer_serial_num' => $asset->ManufacturerSerialNum,
            'parent_serial_num' => $asset->ParentSerialNum,
            'item_number' => $asset->ItemNumber,
            'form_factor' => $asset->FormFactor,
            'speed' => $asset->Speed,
            'memory' => $asset->Memory,
            'storage_capacity' => $asset->StorageCapacity,
            'dual' => $asset->Dual,
            'quad' => $asset->Quad,
            'optical_1' => $asset->Optical1,
            'optical_2' => $asset->Optical2,
            'nic' => $asset->NIC,
            'video' => $asset->Video,
            'color' => $asset->Color,
            'adapter' => $asset->Adapter,
            'screen_size' => $asset->ScreenSize,
            'battery' => $asset->Battery,
            'wifi' => $asset->WiFi,
            'docking_station' => $asset->DockingStation,
            'stylus' => $asset->Stylus,
            'firewire' => $asset->Firewire,
            'keyboard' => $asset->Keyboard,
            'mouse' => $asset->Mouse,
            'cartridge' => $asset->Cartridge,
            'coa' => $asset->COA,
            'osx_description' => $asset->OSXDescription,
            'condition' => $asset->Condition,
            'date_code' => $asset->DateCode,
            'comments' => $asset->Comments,
            'additional_comments' => $asset->AdditionalComments,
            'hard_drive_serial_num' => $asset->HardDriveSerialNum,
            'asset_tag' => $asset->AssetTag,
            'status' => $asset->Status,
            'settlement_amount' => $asset->SettlementAmount,
            'net_settlement' => $asset->NetSettlement,
            'cert_of_data_wipe_num' => $asset->CertOfDataWipeNum,
            'cert_of_destruction_num' => $asset->CertOfDestructionNum,
            'import_date_time' => $asset->ImportDateTime,
            'update_date_time' => $asset->UpdateDateTime
        ];

        if (!$translatedAssetArray['import_date_time']) {
            if ($translatedAssetArray['update_date_time']) {
                $translatedAssetArray['import_date_time'] = $translatedAssetArray['update_date_time'];
            }
            else {
                $translatedAssetArray['import_date_time'] = Carbon::now();
            }
        }

        if (!$translatedAssetArray['update_date_time']) {
            $translatedAssetArray['update_date_time'] = Carbon::now();
        }

        return $translatedAssetArray;
    }
}
