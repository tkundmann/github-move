<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Asset
 *
 * @property int $id
 * @property \Carbon\Carbon $lotDate
 * @property string $lotNumber
 * @property string $billOfLading
 * @property string $carrier
 * @property string $poNumber
 * @property string $vendorOrderNumber
 * @property string $vendor
 * @property string $vendorClient
 * @property \Carbon\Carbon $dateArrived
 * @property \Carbon\Carbon $shipmentDate
 * @property string $barcodeNumber
 * @property string $productFamily
 * @property string $manufacturer
 * @property string $manufacturerModelNum
 * @property string $manufacturerPartNum
 * @property string $manufacturerSerialNum
 * @property string $parentSerialNum
 * @property string $itemNumber
 * @property string $formFactor
 * @property string $speed
 * @property string $memory
 * @property string $storageCapacity
 * @property string $dual
 * @property string $quad
 * @property string $optical1
 * @property string $optical2
 * @property string $nic
 * @property string $video
 * @property string $color
 * @property string $adapter
 * @property string $screenSize
 * @property string $battery
 * @property string $wifi
 * @property string $dockingStation
 * @property string $stylus
 * @property string $firewire
 * @property string $keyboard
 * @property string $mouse
 * @property string $cartridge
 * @property string $coa
 * @property string $osxDescription
 * @property string $condition
 * @property integer $dateCode
 * @property string $comments
 * @property string $additionalComments
 * @property string $hardDriveSerialNum
 * @property string $assetTag
 * @property string $status
 * @property float $settlementAmount
 * @property float $netSettlement
 * @property string $certOfDataWipeNum
 * @property string $certOfDestructionNum
 * @property \Carbon\Carbon $importDateTime
 * @property \Carbon\Carbon $updateDateTime
 * @property-read \App\Data\Models\Shipment $shipment
 */
class Asset extends Model
{
    use Eloquence, Mappable, Sortable;
    
    const CREATED_AT = 'import_date_time';
    const UPDATED_AT = 'update_date_time';

    protected $table = 'asset';

    protected $dates = [
        'lot_date',
        'date_arrived',
        'shipment_date',
        'import_date_time',
        'update_date_time'
    ];
    protected $maps = [
        // 'id' => 'id',
        'lotDate' => 'lot_date',
        'lotNumber' => 'lot_number',
        'billOfLading' => 'bill_of_lading',
        // 'carrier' => 'carrier',
        'poNumber' => 'po_number',
        'vendorOrderNumber' => 'vendor_order_number',
        // 'vendor' => 'vendor',
        'vendorClient' => 'vendor_client',
        'dateArrived' => 'date_arrived',
        'shipmentDate' => 'shipment_date',
        'barcodeNumber' => 'barcode_number',
        'productFamily' => 'product_family',
        // 'manufacturer' => 'manufacturer',
        'manufacturerModelNum' => 'manufacturer_model_num',
        'manufacturerPartNum' => 'manufacturer_part_num',
        'manufacturerSerialNum' => 'manufacturer_serial_num',
        'parentSerialNum' => 'parent_serial_num',
        'itemNumber' => 'item_number',
        'formFactor' => 'form_factor',
        // 'speed' => 'speed',
        // 'memory' => 'memory',
        'storageCapacity' => 'storage_capacity',
        // 'dual' => 'dual',
        // 'quad' => 'quad',
        'optical1' => 'optical_1',
        'optical2' => 'optical_2',
        // 'nic' => 'nic',
        // 'video' => 'video',
        // 'color' => 'color',
        // 'adapter' => 'adapter',
        'screenSize' => 'screen_size',
        // 'battery' => 'battery',
        // 'wifi' => 'wifi',
        'dockingStation' => 'docking_station',
        // 'stylus' => 'stylus',
        // 'firewire' => 'firewire',
        // 'keyboard' => 'keyboard',
        // 'mouse' => 'mouse',
        // 'cartridge' => 'cartridge',
        // 'coa' => 'coa',
        'osxDescription' => 'osx_description',
        // 'condition' => 'condition',
        'dateCode' => 'date_code',
        // 'comments' => 'comments',
        'additionalComments' => 'additional_comments',
        'hardDriveSerialNum' => 'hard_drive_serial_num',
        'assetTag' => 'asset_tag',
        // 'status' => 'status',
        'settlementAmount' => 'settlement_amount',
        'netSettlement' => 'net_settlement',
        'certOfDataWipeNum' => 'cert_of_data_wipe_num',
        'certOfDestructionNum' => 'cert_of_destruction_num',
        'importDateTime' => 'import_date_time',
        'updateDateTime' => 'update_date_time'
    ];

    public function shipment()
    {
        return $this->belongsTo('App\Data\Models\Shipment', 'lot_number', 'lot_number');
    }

    // --

    static function createFromLotControl(SimpleXMLElement $xml, SimpleXMLElement $lotControl) {
        $asset = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if ((isset($lotControl->LOT_DATE)) && (strlen($lotControl->LOT_DATE) > 0)) {
            $asset->lotDate = DateTime::createFromFormat('m/d/y', $lotControl->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($lotControl->attributes()['LOT_NUMBER'])) && (strlen($lotControl->attributes()['LOT_NUMBER']) > 0)) {
            $asset->lotNumber = $lotControl->attributes()->LOT_NUMBER->__toString();
        }
        if ((isset($lotControl->BILL_OF_LADING)) && (strlen($lotControl->BILL_OF_LADING) > 0)) {
            $asset->billOfLading = $lotControl->BILL_OF_LADING->__toString();
        }
        if ((isset($lotControl->FREIGHT_CARRIER)) && (strlen($lotControl->FREIGHT_CARRIER) > 0)) {
            $asset->carrier = $lotControl->FREIGHT_CARRIER->__toString();
        }
        if ((isset($lotControl->PO_NUMBER)) && (strlen($lotControl->PO_NUMBER) > 0)) {
            $asset->poNumber = $lotControl->PO_NUMBER->__toString();
        }
        if ((isset($lotControl->VENDOR_SHIPMENT_NUMBER)) && (strlen($lotControl->VENDOR_SHIPMENT_NUMBER) > 0)) {
            $asset->vendorOrderNumber = $lotControl->VENDOR_SHIPMENT_NUMBER->__toString();
        }
        if ((isset($lotControl->VENDOR_NAME)) && (strlen($lotControl->VENDOR_NAME) > 0)) {
            $asset->vendor = $lotControl->VENDOR_NAME->__toString();
        }
        if ((isset($lotControl->VENDOR_CLIENT)) && (strlen($lotControl->VENDOR_CLIENT) > 0)) {
            $asset->vendorClient = $lotControl->VENDOR_CLIENT->__toString();
        }
        if ((isset($lotControl->DATE_RECEIVED)) && (strlen($lotControl->DATE_RECEIVED) > 0)) {
            $asset->dateArrived = DateTime::createFromFormat('m/d/y', $lotControl->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->SHIPMENT_DATE)) && (strlen($xml->SHIPMENT_DATE) > 0)) {
            $asset->shipmentDate = DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->attributes()['SERIAL_NUMBER'])) && (strlen($xml->attributes()['SERIAL_NUMBER']) > 0)) {
            $asset->barcodeNumber = $xml->attributes()->SERIAL_NUMBER->__toString();
        }
        if ((isset($xml->PRODUCT_FAMILY)) && (strlen($xml->PRODUCT_FAMILY) > 0)) {
            $asset->productFamily = $xml->PRODUCT_FAMILY->__toString();
        }
        if ((isset($xml->MANUFACTURER)) && (strlen($xml->MANUFACTURER) > 0)) {
            $asset->manufacturer = $xml->MANUFACTURER->__toString();
        }
        if ((isset($xml->MFG_MODEL_NUMBER)) && (strlen($xml->MFG_MODEL_NUMBER) > 0)) {
            $asset->manufacturerModelNum = $xml->MFG_MODEL_NUMBER->__toString();
        }
        if ((isset($xml->MFG_PART_NUMBER)) && (strlen($xml->MFG_PART_NUMBER) > 0)) {
            $asset->manufacturerPartNum = $xml->MFG_PART_NUMBER->__toString();
        }
        if ((isset($xml->MFG_SERIAL_NUMBER)) && (strlen($xml->MFG_SERIAL_NUMBER) > 0)) {
            $asset->manufacturerSerialNum = $xml->MFG_SERIAL_NUMBER->__toString();
        }
        if ((isset($xml->PARENT_SERIAL_NO)) && (strlen($xml->PARENT_SERIAL_NO) > 0)) {
            $asset->parentSerialNum = $xml->PARENT_SERIAL_NO->__toString();
        }
        if ((isset($xml->ITEM_NUMBER)) && (strlen($xml->ITEM_NUMBER) > 0)) {
            $asset->itemNumber = $xml->ITEM_NUMBER->__toString();
        }
        if ((isset($xml->FORM_FACTOR)) && (strlen($xml->FORM_FACTOR) > 0)) {
            $asset->formFactor = $xml->FORM_FACTOR->__toString();
        }
        if ((isset($xml->SPEED)) && (strlen($xml->SPEED) > 0)) {
            $asset->speed = $xml->SPEED->__toString();
        }
        if ((isset($xml->MEMORY)) && (strlen($xml->MEMORY) > 0)) {
            $asset->memory = $xml->MEMORY->__toString();
        }
        if ((isset($xml->STORAGE_CAPACITY)) && (strlen($xml->STORAGE_CAPACITY) > 0)) {
            $asset->storageCapacity = $xml->STORAGE_CAPACITY->__toString();
        }
        if ((isset($xml->DUAL)) && (strlen($xml->DUAL) > 0)) {
            $asset->dual = $xml->DUAL->__toString();
        }
        if ((isset($xml->QUAD)) && (strlen($xml->QUAD) > 0)) {
            $asset->quad = $xml->QUAD->__toString();
        }
        if ((isset($xml->OPTICAL_1)) && (strlen($xml->OPTICAL_1) > 0)) {
            $asset->optical1 = $xml->OPTICAL_1->__toString();
        }
        if ((isset($xml->OPTICAL_2)) && (strlen($xml->OPTICAL_2) > 0)) {
            $asset->optical2 = $xml->OPTICAL_2->__toString();
        }
        if ((isset($xml->NIC)) && (strlen($xml->NIC) > 0)) {
            $asset->nic = $xml->NIC->__toString();
        }
        if ((isset($xml->VIDEO)) && (strlen($xml->VIDEO) > 0)) {
            $asset->video = $xml->VIDEO->__toString();
        }
        if ((isset($xml->COLOR)) && (strlen($xml->COLOR) > 0)) {
            $asset->color = $xml->COLOR->__toString();
        }
        if ((isset($xml->ADAPTER)) && (strlen($xml->ADAPTER) > 0)) {
            $asset->adapter = $xml->ADAPTER->__toString();
        }
        if ((isset($xml->SCREEN_SIZE)) && (strlen($xml->SCREEN_SIZE) > 0)) {
            $asset->screenSize = $xml->SCREEN_SIZE->__toString();
        }
        if ((isset($xml->BATTERY)) && (strlen($xml->BATTERY) > 0)) {
            $asset->battery = $xml->BATTERY->__toString();
        }
        if ((isset($xml->WIFI)) && (strlen($xml->WIFI) > 0)) {
            $asset->wifi = $xml->WIFI->__toString();
        }
        if ((isset($xml->DOCKING)) && (strlen($xml->DOCKING) > 0)) {
            $asset->dockingStation = $xml->DOCKING->__toString();
        }
        if ((isset($xml->STYLUS)) && (strlen($xml->STYLUS) > 0)) {
            $asset->stylus = $xml->STYLUS->__toString();
        }
        if ((isset($xml->FIREWIRE)) && (strlen($xml->FIREWIRE) > 0)) {
            $asset->firewire = $xml->FIREWIRE->__toString();
        }
        if ((isset($xml->KEYBOARD)) && (strlen($xml->KEYBOARD) > 0)) {
            $asset->keyboard = $xml->KEYBOARD->__toString();
        }
        if ((isset($xml->MOUSE)) && (strlen($xml->MOUSE) > 0)) {
            $asset->mouse = $xml->MOUSE->__toString();
        }
        if ((isset($xml->CARTRIDGE)) && (strlen($xml->CARTRIDGE) > 0)) {
            $asset->cartridge = $xml->CARTRIDGE->__toString();
        }
        if ((isset($xml->COA)) && (strlen($xml->COA) > 0)) {
            $asset->coa = $xml->COA->__toString();
        }
        if ((isset($xml->OSX_DESCRIPTION)) && (strlen($xml->OSX_DESCRIPTION) > 0)) {
            $asset->osxDescription = $xml->OSX_DESCRIPTION->__toString();
        }
        if ((isset($xml->CONDITION)) && (strlen($xml->CONDITION) > 0)) {
            $asset->condition = $xml->CONDITION->__toString();
        }
        if ((isset($xml->DATE_CODE)) && (strlen($xml->DATE_CODE) > 0)) {
            $asset->dateCode = intval($xml->DATE_CODE->__toString());
        }
        if ((isset($xml->COMMENTS)) && (strlen($xml->COMMENTS) > 0)) {
            $asset->comments = $xml->COMMENTS->__toString();
        }
        if ((isset($xml->ADDITIONAL_COMMENTS)) && (strlen($xml->ADDITIONAL_COMMENTS) > 0)) {
            $asset->additionalComments = $xml->ADDITIONAL_COMMENTS->__toString();
        }
        if ((isset($xml->HARD_DRIVE_SERIAL_NUMBER)) && (strlen($xml->HARD_DRIVE_SERIAL_NUMBER) > 0)) {
            $asset->hardDriveSerialNum = $xml->HARD_DRIVE_SERIAL_NUMBER->__toString();
        }
        if ((isset($xml->ASSET_TAG)) && (strlen($xml->ASSET_TAG) > 0)) {
            $asset->assetTag = $xml->ASSET_TAG->__toString();
        }
        if ((isset($xml->STATUS)) && (strlen($xml->STATUS) > 0)) {
            $asset->status = $xml->STATUS->__toString();
        }
        if ((isset($xml->SETTLEMENT_AMOUNT)) && (strlen($xml->SETTLEMENT_AMOUNT) > 0)) {
            $asset->settlementAmount = floatval($xml->SETTLEMENT_AMOUNT->__toString());
        }
        if ((isset($xml->PERCENT_PAYBACK)) && (strlen($xml->PERCENT_PAYBACK) > 0)) {
            $asset->netSettlement = floatval($xml->PERCENT_PAYBACK->__toString());
        }
        if ((isset($lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER)) && (strlen($lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0)) {
            $asset->certOfDataWipeNum = $lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
        }
        if ((isset($lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER)) && (strlen($lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0)) {
            $asset->certOfDestructionNum = $lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
        }

        return $asset;
    }
    
    static function createFromAssetsDetail(SimpleXMLElement $xml) {
        $asset = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if ((isset($xml->LOT_DATE)) && (strlen($xml->LOT_DATE) > 0)) {
            $asset->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->LOT_NO)) && (strlen($xml->LOT_NO) > 0)) {
            $asset->lotNumber = $xml->LOT_NO->__toString();
        }
        if ((isset($xml->BILL_OF_LADING)) && (strlen($xml->BILL_OF_LADING) > 0)) {
            $asset->billOfLading = $xml->BILL_OF_LADING->__toString();
        }
        if ((isset($xml->FREIGHT_CARRIER)) && (strlen($xml->FREIGHT_CARRIER) > 0)) {
            $asset->carrier = $xml->FREIGHT_CARRIER->__toString();
        }
        if ((isset($xml->PO_NUMBER)) && (strlen($xml->PO_NUMBER) > 0)) {
            $asset->poNumber = $xml->PO_NUMBER->__toString();
        }
        if ((isset($xml->VENDOR_SHIPMENT_NUMBER)) && (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0)) {
            $asset->vendorOrderNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
        }
        if ((isset($xml->VENDOR_NAME)) && (strlen($xml->VENDOR_NAME) > 0)) {
            $asset->vendor = $xml->VENDOR_NAME->__toString();
        }
        if ((isset($xml->VENDOR_CLIENT)) && (strlen($xml->VENDOR_CLIENT) > 0)) {
            $asset->vendorClient = $xml->VENDOR_CLIENT->__toString();
        }
        if ((isset($xml->DATE_RECEIVED)) && (strlen($xml->DATE_RECEIVED) > 0)) {
            $asset->dateArrived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->SHIPMENT_DATE)) && (strlen($xml->SHIPMENT_DATE) > 0)) {
            $asset->shipmentDate = DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->attributes()['SERIAL_NO'])) && (strlen($xml->attributes()['SERIAL_NO']) > 0)) {
            $asset->barcodeNumber = $xml->attributes()->SERIAL_NO->__toString();
        }
        if ((isset($xml->PRODUCT_FAMILY)) && (strlen($xml->PRODUCT_FAMILY) > 0)) {
            $asset->productFamily = $xml->PRODUCT_FAMILY->__toString();
        }
        if ((isset($xml->MFG)) && (strlen($xml->MFG) > 0)) {
            $asset->manufacturer = $xml->MFG->__toString();
        }
        if ((isset($xml->MFG_MODEL_NUMBER)) && (strlen($xml->MFG_MODEL_NUMBER) > 0)) {
            $asset->manufacturerModelNum = $xml->MFG_MODEL_NUMBER->__toString();
        }
        if ((isset($xml->MFG_PART_NUMBER)) && (strlen($xml->MFG_PART_NUMBER) > 0)) {
            $asset->manufacturerPartNum = $xml->MFG_PART_NUMBER->__toString();
        }
        if ((isset($xml->MFG_SERIAL_NUMBER)) && (strlen($xml->MFG_SERIAL_NUMBER) > 0)) {
            $asset->manufacturerSerialNum = $xml->MFG_SERIAL_NUMBER->__toString();
        }
        if ((isset($xml->PARENT_SERIAL_NO)) && (strlen($xml->PARENT_SERIAL_NO) > 0)) {
            $asset->parentSerialNum = $xml->PARENT_SERIAL_NO->__toString();
        }
        if ((isset($xml->ITEM_NUMBER)) && (strlen($xml->ITEM_NUMBER) > 0)) {
            $asset->itemNumber = $xml->ITEM_NUMBER->__toString();
        }
        if ((isset($xml->FORM_FACTOR)) && (strlen($xml->FORM_FACTOR) > 0)) {
            $asset->formFactor = $xml->FORM_FACTOR->__toString();
        }
        if ((isset($xml->SPEED)) && (strlen($xml->SPEED) > 0)) {
            $asset->speed = $xml->SPEED->__toString();
        }
        if ((isset($xml->MEMORY)) && (strlen($xml->MEMORY) > 0)) {
            $asset->memory = $xml->MEMORY->__toString();
        }
        if ((isset($xml->STORAGE_CAPACITY)) && (strlen($xml->STORAGE_CAPACITY) > 0)) {
            $asset->storageCapacity = $xml->STORAGE_CAPACITY->__toString();
        }
        if ((isset($xml->DUAL)) && (strlen($xml->DUAL) > 0)) {
            $asset->dual = $xml->DUAL->__toString();
        }
        if ((isset($xml->QUAD)) && (strlen($xml->QUAD) > 0)) {
            $asset->quad = $xml->QUAD->__toString();
        }
        if ((isset($xml->OPTICAL_1)) && (strlen($xml->OPTICAL_1) > 0)) {
            $asset->optical1 = $xml->OPTICAL_1->__toString();
        }
        if ((isset($xml->OPTICAL_2)) && (strlen($xml->OPTICAL_2) > 0)) {
            $asset->optical2 = $xml->OPTICAL_2->__toString();
        }
        if ((isset($xml->NIC)) && (strlen($xml->NIC) > 0)) {
            $asset->nic = $xml->NIC->__toString();
        }
        if ((isset($xml->VIDEO)) && (strlen($xml->VIDEO) > 0)) {
            $asset->video = $xml->VIDEO->__toString();
        }
        if ((isset($xml->COLOR)) && (strlen($xml->COLOR) > 0)) {
            $asset->color = $xml->COLOR->__toString();
        }
        if ((isset($xml->ADAPTER)) && (strlen($xml->ADAPTER) > 0)) {
            $asset->adapter = $xml->ADAPTER->__toString();
        }
        if ((isset($xml->SCREEN_SIZE)) && (strlen($xml->SCREEN_SIZE) > 0)) {
            $asset->screenSize = $xml->SCREEN_SIZE->__toString();
        }
        if ((isset($xml->BATTERY)) && (strlen($xml->BATTERY) > 0)) {
            $asset->battery = $xml->BATTERY->__toString();
        }
        if ((isset($xml->WIFI)) && (strlen($xml->WIFI) > 0)) {
            $asset->wifi = $xml->WIFI->__toString();
        }
        if ((isset($xml->DOCKING)) && (strlen($xml->DOCKING) > 0)) {
            $asset->dockingStation = $xml->DOCKING->__toString();
        }
        if ((isset($xml->STYLUS)) && (strlen($xml->STYLUS) > 0)) {
            $asset->stylus = $xml->STYLUS->__toString();
        }
        if ((isset($xml->FIREWIRE)) && (strlen($xml->FIREWIRE) > 0)) {
            $asset->firewire = $xml->FIREWIRE->__toString();
        }
        if ((isset($xml->KEYBOARD)) && (strlen($xml->KEYBOARD) > 0)) {
            $asset->keyboard = $xml->KEYBOARD->__toString();
        }
        if ((isset($xml->MOUSE)) && (strlen($xml->MOUSE) > 0)) {
            $asset->mouse = $xml->MOUSE->__toString();
        }
        if ((isset($xml->CARTRIDGE)) && (strlen($xml->CARTRIDGE) > 0)) {
            $asset->cartridge = $xml->CARTRIDGE->__toString();
        }
        if ((isset($xml->COA)) && (strlen($xml->COA) > 0)) {
            $asset->coa = $xml->COA->__toString();
        }
        if ((isset($xml->OSX_DESCRIPTION)) && (strlen($xml->OSX_DESCRIPTION) > 0)) {
            $asset->osxDescription = $xml->OSX_DESCRIPTION->__toString();
        }
        if ((isset($xml->CONDITION)) && (strlen($xml->CONDITION) > 0)) {
            $asset->condition = $xml->CONDITION->__toString();
        }
        if ((isset($xml->DATE_CODE)) && (strlen($xml->DATE_CODE) > 0)) {
            $asset->dateCode = intval($xml->DATE_CODE->__toString());
        }
        if ((isset($xml->COMMENTS)) && (strlen($xml->COMMENTS) > 0)) {
            $asset->comments = $xml->COMMENTS->__toString();
        }
        if ((isset($xml->ADDITIONAL_COMMENTS)) && (strlen($xml->ADDITIONAL_COMMENTS) > 0)) {
            $asset->additionalComments = $xml->ADDITIONAL_COMMENTS->__toString();
        }
        if ((isset($xml->HARD_DRIVE_SERIAL_NUMBER)) && (strlen($xml->HARD_DRIVE_SERIAL_NUMBER) > 0)) {
            $asset->hardDriveSerialNum = $xml->HARD_DRIVE_SERIAL_NUMBER->__toString();
        }
        if ((isset($xml->ASSET_TAG)) && (strlen($xml->ASSET_TAG) > 0)) {
            $asset->assetTag = $xml->ASSET_TAG->__toString();
        }
        if ((isset($xml->STATUS)) && (strlen($xml->STATUS) > 0)) {
            $asset->status = $xml->STATUS->__toString();
        }
        if ((isset($xml->SETTLEMENT_AMOUNT)) && (strlen($xml->SETTLEMENT_AMOUNT) > 0)) {
            $asset->settlementAmount = floatval($xml->SETTLEMENT_AMOUNT->__toString());
        }
        if ((isset($xml->NET_SETTLEMENT)) && (strlen($xml->NET_SETTLEMENT) > 0)) {
            $asset->netSettlement = floatval($xml->NET_SETTLEMENT->__toString());
        }
        if ((isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0)) {
            $asset->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
        }
        if ((isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0)) {
            $asset->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
        }

        return $asset;
    }
}