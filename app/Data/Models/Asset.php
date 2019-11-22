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
 * @property string $biosManufacturerSerialNum
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
 * @property string $securityLock
 * @property string $securityLockResolved
 * @property string $hdsnConfiguration
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
        'biosManufacturerSerialNum' => 'bios_manufacturer_serial_num',
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
        'securityLock' => 'security_lock',
        'securityLockResolved' => 'security_lock_resolved',
        'settlementAmount' => 'settlement_amount',
        'netSettlement' => 'net_settlement',
        'certOfDataWipeNum' => 'cert_of_data_wipe_num',
        'certOfDestructionNum' => 'cert_of_destruction_num',
        'importDateTime' => 'import_date_time',
        'updateDateTime' => 'update_date_time',
        'hdsnConfiguration' => 'hdsn_configuration'
    ];

    public function shipment()
    {
        return $this->belongsTo('App\Data\Models\Shipment', 'lot_number', 'lot_number');
    }

    // --

    static function createFromLotControl(SimpleXMLElement $xml, SimpleXMLElement $lotControl) {
        $asset = new self();

        if (isset($lotControl->LOT_DATE)) {
            $asset->lotDate = null;
            if (strlen($lotControl->LOT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/y', $lotControl->LOT_DATE->__toString())) {
                    $asset->lotDate = DateTime::createFromFormat('m/d/y', $lotControl->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $lotControl->LOT_DATE->__toString())) {
                    $asset->lotDate = DateTime::createFromFormat('m/d/Y', $lotControl->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($lotControl->attributes()['LOT_NUMBER'])) {
            $asset->lotNumber = null;
            if (strlen($lotControl->attributes()['LOT_NUMBER']) > 0) {
                $asset->lotNumber = $lotControl->attributes()->LOT_NUMBER->__toString();
            }
        }
        if (isset($lotControl->BILL_OF_LADING)) {
            $asset->billOfLading = null;
            if (strlen($lotControl->BILL_OF_LADING) > 0) {
                $asset->billOfLading = $lotControl->BILL_OF_LADING->__toString();
            }
        }
        if (isset($lotControl->FREIGHT_CARRIER)) {
            $asset->carrier = null;
            if (strlen($lotControl->FREIGHT_CARRIER) > 0) {
                $asset->carrier = $lotControl->FREIGHT_CARRIER->__toString();
            }
        }
        if (isset($lotControl->PO_NUMBER)) {
            $asset->poNumber = null;
            if (strlen($lotControl->PO_NUMBER) > 0) {
                $asset->poNumber = $lotControl->PO_NUMBER->__toString();
            }
        }
        if (isset($lotControl->VENDOR_SHIPMENT_NUMBER)) {
            $asset->vendorOrderNumber = null;
            if (strlen($lotControl->VENDOR_SHIPMENT_NUMBER) > 0) {
                $asset->vendorOrderNumber = $lotControl->VENDOR_SHIPMENT_NUMBER->__toString();
            }
        }
        if (isset($lotControl->VENDOR_NAME)) {
            $asset->vendor = null;
            if (strlen($lotControl->VENDOR_NAME) > 0) {
                $asset->vendor = $lotControl->VENDOR_NAME->__toString();
            }
        }
        if (isset($lotControl->VENDOR_CLIENT)) {
            $asset->vendorClient = null;
            if (strlen($lotControl->VENDOR_CLIENT) > 0) {
                $asset->vendorClient = $lotControl->VENDOR_CLIENT->__toString();
            }
        }
        if (isset($lotControl->DATE_RECEIVED)) {
            $asset->dateArrived = null;
            if (strlen($lotControl->DATE_RECEIVED) > 0) {
                if (DateTime::createFromFormat('m/d/y', $lotControl->DATE_RECEIVED->__toString())) {
                    $asset->dateArrived = DateTime::createFromFormat('m/d/y', $lotControl->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $lotControl->DATE_RECEIVED->__toString())) {
                    $asset->dateArrived = DateTime::createFromFormat('m/d/Y', $lotControl->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($xml->SHIPMENT_DATE)) {
            $asset->shipmentDate = null;
            if (strlen($xml->SHIPMENT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())) {
                    $asset->shipmentDate = DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $xml->SHIPMENT_DATE->__toString())) {
                    $asset->shipmentDate = DateTime::createFromFormat('m/d/Y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($xml->attributes()['SERIAL_NUMBER'])) {
            if (strlen($xml->attributes()['SERIAL_NUMBER']) > 0) {
                $asset->barcodeNumber = $xml->attributes()->SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->PRODUCT_FAMILY)) {
            $asset->productFamily = null;
            if (strlen($xml->PRODUCT_FAMILY) > 0) {
                $asset->productFamily = $xml->PRODUCT_FAMILY->__toString();
            }
        }
        if (isset($xml->MANUFACTURER)) {
            $asset->manufacturer = null;
            if (strlen($xml->MANUFACTURER) > 0) {
                $asset->manufacturer = $xml->MANUFACTURER->__toString();
            }
        }
        if (isset($xml->MFG_MODEL_NUMBER)) {
            $asset->manufacturerModelNum = null;
            if (strlen($xml->MFG_MODEL_NUMBER) > 0) {
                $asset->manufacturerModelNum = $xml->MFG_MODEL_NUMBER->__toString();
            }
        }
        if (isset($xml->MFG_PART_NUMBER)) {
            $asset->manufacturerPartNum = null;
            if (strlen($xml->MFG_PART_NUMBER) > 0) {
                $asset->manufacturerPartNum = $xml->MFG_PART_NUMBER->__toString();
            }
        }
        if (isset($xml->MFG_SERIAL_NUMBER)) {
            $asset->manufacturerSerialNum = null;
            if (strlen($xml->MFG_SERIAL_NUMBER) > 0) {
                $asset->manufacturerSerialNum = $xml->MFG_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->BIOS_MFG_SERIAL_NUMBER)) {
            $asset->biosManufacturerSerialNum = null;
            if (strlen($xml->BIOS_MFG_SERIAL_NUMBER) > 0) {
                $asset->biosManufacturerSerialNum = $xml->BIOS_MFG_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->PARENT_SERIAL_NO)) {
            $asset->parentSerialNum = null;
            if (strlen($xml->PARENT_SERIAL_NO) > 0) {
                $asset->parentSerialNum = $xml->PARENT_SERIAL_NO->__toString();
            }
        }
        if (isset($xml->ITEM_NUMBER)) {
            $asset->itemNumber = null;
            if (strlen($xml->ITEM_NUMBER) > 0) {
                $asset->itemNumber = $xml->ITEM_NUMBER->__toString();
            }
        }
        if (isset($xml->FORM_FACTOR)) {
            $asset->formFactor = null;
            if (strlen($xml->FORM_FACTOR) > 0) {
                $asset->formFactor = $xml->FORM_FACTOR->__toString();
            }
        }
        if (isset($xml->SPEED)) {
            $asset->speed = null;
            if (strlen($xml->SPEED) > 0) {
                $asset->speed = $xml->SPEED->__toString();
            }
        }
        if (isset($xml->MEMORY)) {
            $asset->memory = null;
            if (strlen($xml->MEMORY) > 0) {
                $asset->memory = $xml->MEMORY->__toString();
            }
        }
        if (isset($xml->STORAGE_CAPACITY)) {
            $asset->storageCapacity = null;
            if (strlen($xml->STORAGE_CAPACITY) > 0) {
                $asset->storageCapacity = $xml->STORAGE_CAPACITY->__toString();
            }
        }
        if (isset($xml->DUAL)) {
            $asset->dual = null;
            if (strlen($xml->DUAL) > 0) {
                $asset->dual = $xml->DUAL->__toString();
            }
        }
        if (isset($xml->QUAD)) {
            $asset->quad = null;
            if (strlen($xml->QUAD) > 0) {
                $asset->quad = $xml->QUAD->__toString();
            }
        }
        if (isset($xml->OPTICAL_1)) {
            $asset->optical1 = null;
            if (strlen($xml->OPTICAL_1) > 0) {
                $asset->optical1 = $xml->OPTICAL_1->__toString();
            }
        }
        if (isset($xml->OPTICAL_2)) {
            $asset->optical2 = null;
            if (strlen($xml->OPTICAL_2) > 0) {
                $asset->optical2 = $xml->OPTICAL_2->__toString();
            }
        }
        if (isset($xml->NIC)) {
            $asset->nic = null;
            if (strlen($xml->NIC) > 0) {
                $asset->nic = $xml->NIC->__toString();
            }
        }
        if (isset($xml->VIDEO)) {
            $asset->video = null;
            if (strlen($xml->VIDEO) > 0) {
                $asset->video = $xml->VIDEO->__toString();
            }
        }
        if (isset($xml->COLOR)) {
            $asset->color = null;
            if (strlen($xml->COLOR) > 0) {
                $asset->color = $xml->COLOR->__toString();
            }
        }
        if (isset($xml->ADAPTER)) {
            $asset->adapter = null;
            if (strlen($xml->ADAPTER) > 0) {
                $asset->adapter = $xml->ADAPTER->__toString();
            }
        }
        if (isset($xml->SCREEN_SIZE)) {
            $asset->screenSize = null;
            if (strlen($xml->SCREEN_SIZE) > 0) {
                $asset->screenSize = $xml->SCREEN_SIZE->__toString();
            }
        }
        if (isset($xml->BATTERY)) {
            $asset->battery = null;
            if (strlen($xml->BATTERY) > 0) {
                $asset->battery = $xml->BATTERY->__toString();
            }
        }
        if (isset($xml->WIFI)) {
            $asset->wifi = null;
            if (strlen($xml->WIFI) > 0) {
                $asset->wifi = $xml->WIFI->__toString();
            }
        }
        if (isset($xml->DOCKING)) {
            $asset->dockingStation = null;
            if (strlen($xml->DOCKING) > 0) {
                $asset->dockingStation = $xml->DOCKING->__toString();
            }
        }
        if (isset($xml->STYLUS)) {
            $asset->stylus = null;
            if (strlen($xml->STYLUS) > 0) {
                $asset->stylus = $xml->STYLUS->__toString();
            }
        }
        if (isset($xml->FIREWIRE)) {
            $asset->firewire = null;
            if (strlen($xml->FIREWIRE) > 0) {
                $asset->firewire = $xml->FIREWIRE->__toString();
            }
        }
        if (isset($xml->KEYBOARD)) {
            $asset->keyboard = null;
            if (strlen($xml->KEYBOARD) > 0) {
                $asset->keyboard = $xml->KEYBOARD->__toString();
            }
        }
        if (isset($xml->MOUSE)) {
            $asset->mouse = null;
            if (strlen($xml->MOUSE) > 0) {
                $asset->mouse = $xml->MOUSE->__toString();
            }
        }
        if (isset($xml->CARTRIDGE)) {
            $asset->cartridge = null;
            if (strlen($xml->CARTRIDGE) > 0) {
                $asset->cartridge = $xml->CARTRIDGE->__toString();
            }
        }
        if (isset($xml->COA)) {
            $asset->coa = null;
            if (strlen($xml->COA) > 0) {
                $asset->coa = $xml->COA->__toString();
            }
        }
        if (isset($xml->OSX_DESCRIPTION)) {
            $asset->osxDescription = null;
            if (strlen($xml->OSX_DESCRIPTION) > 0) {
                $asset->osxDescription = $xml->OSX_DESCRIPTION->__toString();
            }
        }
        if (isset($xml->CONDITION)) {
            $asset->condition = null;
            if (strlen($xml->CONDITION) > 0) {
                $asset->condition = $xml->CONDITION->__toString();
            }
        }
        if (isset($xml->DATE_CODE)) {
            $asset->dateCode = null;
            if (strlen($xml->DATE_CODE) > 0) {
                $asset->dateCode = intval($xml->DATE_CODE->__toString());
            }
        }
        if (isset($xml->COMMENTS)) {
            $asset->comments = null;
            if (strlen($xml->COMMENTS) > 0) {
                $asset->comments = $xml->COMMENTS->__toString();
            }
        }
        if (isset($xml->ADDITIONAL_COMMENTS)) {
            $asset->additionalComments = null;
            if (strlen($xml->ADDITIONAL_COMMENTS) > 0) {
                $asset->additionalComments = $xml->ADDITIONAL_COMMENTS->__toString();
            }
        }
        if (isset($xml->HARD_DRIVE_SERIAL_NUMBER)) {
            $asset->hardDriveSerialNum = null;
            if (strlen($xml->HARD_DRIVE_SERIAL_NUMBER) > 0) {
                $asset->hardDriveSerialNum = $xml->HARD_DRIVE_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->ASSET_TAG)) {
            $asset->assetTag = null;
            if (strlen($xml->ASSET_TAG) > 0) {
                $asset->assetTag = $xml->ASSET_TAG->__toString();
            }
        }
        if (isset($xml->STATUS)) {
            $asset->status = null;
            if (strlen($xml->STATUS) > 0) {
                $asset->status = $xml->STATUS->__toString();
            }
        }
        if (isset($xml->SECURITY_LOCK)) {
            $asset->securityLock = null;
            if (strlen($xml->SECURITY_LOCK) > 0) {
                $asset->securityLock = $xml->SECURITY_LOCK->__toString();
            }
        }
        if (isset($xml->SECURITY_LOCK_RESOLVED)) {
            $asset->securityLockResolved = null;
            if (strlen($xml->SECURITY_LOCK_RESOLVED) > 0) {
                $asset->securityLockResolved = $xml->SECURITY_LOCK_RESOLVED->__toString();
            }
        }
        if (isset($xml->SETTLEMENT_AMOUNT)) {
            $asset->settlementAmount = null;
            if (strlen($xml->SETTLEMENT_AMOUNT) > 0) {
                $asset->settlementAmount = floatval($xml->SETTLEMENT_AMOUNT->__toString());
            }
        }
        if (isset($xml->PERCENT_PAYBACK)) {
            $asset->netSettlement = null;
            if (strlen($xml->PERCENT_PAYBACK) > 0) {
                $asset->netSettlement = floatval($xml->PERCENT_PAYBACK->__toString());
            }
        }
        if (isset($lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER)) {
            $asset->certOfDataWipeNum = null;
            if (strlen($lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0) {
                $asset->certOfDataWipeNum = $lotControl->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
            }
        }
        if (isset($lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER)) {
            $asset->certOfDestructionNum = null;
            if (strlen($lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0) {
                $asset->certOfDestructionNum = $lotControl->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
            }
        }
        if (isset($xml->HDSN_CONFIG)) {
            $asset->hdsnConfiguration = null;
            if (strlen($xml->HDSN_CONFIG) > 0) {
                $asset->hdsnConfiguration = $xml->HDSN_CONFIG->__toString();
            }
        }

        return $asset;
    }

    static function createFromAssetsDetail(SimpleXMLElement $xml) {
        $asset = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if (isset($xml->LOT_DATE)) {
            $asset->lotDate = null;
            if (strlen($xml->LOT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())) {
                    $asset->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())) {
                    $asset->lotDate = DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($xml->LOT_NO)) {
            $asset->lotNumber = null;
            if (strlen($xml->LOT_NO) > 0) {
                $asset->lotNumber = $xml->LOT_NO->__toString();
            }
        }
        if (isset($xml->BILL_OF_LADING)) {
            $asset->billOfLading = null;
            if (strlen($xml->BILL_OF_LADING) > 0) {
                $asset->billOfLading = $xml->BILL_OF_LADING->__toString();
            }
        }
        if (isset($xml->FREIGHT_CARRIER)) {
            $asset->carrier = null;
            if (strlen($xml->FREIGHT_CARRIER) > 0) {
                $asset->carrier = $xml->FREIGHT_CARRIER->__toString();
            }
        }
        if (isset($xml->PO_NUMBER)) {
            $asset->poNumber = null;
            if (strlen($xml->PO_NUMBER) > 0) {
                $asset->poNumber = $xml->PO_NUMBER->__toString();
            }
        }
        if (isset($xml->VENDOR_SHIPMENT_NUMBER)) {
            $asset->vendorOrderNumber = null;
            if (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0) {
                $asset->vendorOrderNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
            }
        }
        if (isset($xml->VENDOR_NAME)) {
            $asset->vendor = null;
            if (strlen($xml->VENDOR_NAME) > 0) {
                $asset->vendor = $xml->VENDOR_NAME->__toString();
            }
        }
        if (isset($xml->VENDOR_CLIENT)) {
            $asset->vendorClient = null;
            if (strlen($xml->VENDOR_CLIENT) > 0) {
                $asset->vendorClient = $xml->VENDOR_CLIENT->__toString();
            }
        }
        if (isset($xml->DATE_RECEIVED)) {
            $asset->dateArrived = null;
            if (strlen($xml->DATE_RECEIVED) > 0) {
                if (DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())) {
                    $asset->dateArrived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())) {
                    $asset->dateArrived = DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($xml->SHIPMENT_DATE)) {
            $asset->shipmentDate = null;
            if (strlen($xml->SHIPMENT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())) {
                    $asset->shipmentDate = DateTime::createFromFormat('m/d/y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $xml->SHIPMENT_DATE->__toString())) {
                    $asset->shipmentDate = DateTime::createFromFormat('m/d/Y', $xml->SHIPMENT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }
        if (isset($xml->attributes()['SERIAL_NO'])) {
            if (strlen($xml->attributes()['SERIAL_NO']) > 0) {
                $asset->barcodeNumber = $xml->attributes()->SERIAL_NO->__toString();
            }
        }
        if (isset($xml->PRODUCT_FAMILY)) {
            $asset->productFamily = null;
            if (strlen($xml->PRODUCT_FAMILY) > 0) {
                $asset->productFamily = $xml->PRODUCT_FAMILY->__toString();
            }
        }
        if (isset($xml->MFG)) {
            $asset->manufacturer = null;
            if (strlen($xml->MFG) > 0) {
                $asset->manufacturer = $xml->MFG->__toString();
            }
        }
        if (isset($xml->MFG_MODEL_NUMBER)) {
            $asset->manufacturerModelNum = null;
            if (strlen($xml->MFG_MODEL_NUMBER) > 0) {
                $asset->manufacturerModelNum = $xml->MFG_MODEL_NUMBER->__toString();
            }
        }
        if (isset($xml->MFG_PART_NUMBER)) {
            $asset->manufacturerPartNum = null;
            if (strlen($xml->MFG_PART_NUMBER) > 0) {
                $asset->manufacturerPartNum = $xml->MFG_PART_NUMBER->__toString();
            }
        }
        if (isset($xml->MFG_SERIAL_NUMBER)) {
            $asset->manufacturerSerialNum = null;
            if (strlen($xml->MFG_SERIAL_NUMBER) > 0) {
                $asset->manufacturerSerialNum = $xml->MFG_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->BIOS_MFG_SERIAL_NUMBER)) {
            $asset->biosManufacturerSerialNum = null;
            if (strlen($xml->BIOS_MFG_SERIAL_NUMBER) > 0) {
                $asset->biosManufacturerSerialNum = $xml->BIOS_MFG_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->PARENT_SERIAL_NO)) {
            $asset->parentSerialNum = null;
            if (strlen($xml->PARENT_SERIAL_NO) > 0) {
                $asset->parentSerialNum = $xml->PARENT_SERIAL_NO->__toString();
            }
        }
        if (isset($xml->ITEM_NUMBER)) {
            $asset->itemNumber = null;
            if (strlen($xml->ITEM_NUMBER) > 0) {
                $asset->itemNumber = $xml->ITEM_NUMBER->__toString();
            }
        }
        if (isset($xml->FORM_FACTOR)) {
            $asset->formFactor = null;
            if (strlen($xml->FORM_FACTOR) > 0) {
                $asset->formFactor = $xml->FORM_FACTOR->__toString();
            }
        }
        if (isset($xml->SPEED)) {
            $asset->speed = null;
            if (strlen($xml->SPEED) > 0) {
                $asset->speed = $xml->SPEED->__toString();
            }
        }
        if (isset($xml->MEMORY)) {
            $asset->memory = null;
            if (strlen($xml->MEMORY) > 0) {
                $asset->memory = $xml->MEMORY->__toString();
            }
        }
        if (isset($xml->STORAGE_CAPACITY)) {
            $asset->storageCapacity = null;
            if (strlen($xml->STORAGE_CAPACITY) > 0) {
                $asset->storageCapacity = $xml->STORAGE_CAPACITY->__toString();
            }
        }
        if (isset($xml->DUAL)) {
            $asset->dual = null;
            if (strlen($xml->DUAL) > 0) {
                $asset->dual = $xml->DUAL->__toString();
            }
        }
        if (isset($xml->QUAD)) {
            $asset->quad = null;
            if (strlen($xml->QUAD) > 0) {
                $asset->quad = $xml->QUAD->__toString();
            }
        }
        if (isset($xml->OPTICAL_1)) {
            $asset->optical1 = null;
            if (strlen($xml->OPTICAL_1) > 0) {
                $asset->optical1 = $xml->OPTICAL_1->__toString();
            }
        }
        if (isset($xml->OPTICAL_2)) {
            $asset->optical2 = null;
            if (strlen($xml->OPTICAL_2) > 0) {
                $asset->optical2 = $xml->OPTICAL_2->__toString();
            }
        }
        if (isset($xml->NIC)) {
            $asset->nic = null;
            if (strlen($xml->NIC) > 0) {
                $asset->nic = $xml->NIC->__toString();
            }
        }
        if (isset($xml->VIDEO)) {
            $asset->video = null;
            if (strlen($xml->VIDEO) > 0) {
                $asset->video = $xml->VIDEO->__toString();
            }
        }
        if (isset($xml->COLOR)) {
            $asset->color = null;
            if (strlen($xml->COLOR) > 0) {
                $asset->color = $xml->COLOR->__toString();
            }
        }
        if (isset($xml->ADAPTER)) {
            $asset->adapter = null;
            if (strlen($xml->ADAPTER) > 0) {
                $asset->adapter = $xml->ADAPTER->__toString();
            }
        }
        if (isset($xml->SCREEN_SIZE)) {
            $asset->screenSize = null;
            if (strlen($xml->SCREEN_SIZE) > 0) {
                $asset->screenSize = $xml->SCREEN_SIZE->__toString();
            }
        }
        if (isset($xml->BATTERY)) {
            $asset->battery = null;
            if (strlen($xml->BATTERY) > 0) {
                $asset->battery = $xml->BATTERY->__toString();
            }
        }
        if (isset($xml->WIFI)) {
            $asset->wifi = null;
            if (strlen($xml->WIFI) > 0) {
                $asset->wifi = $xml->WIFI->__toString();
            }
        }
        if (isset($xml->DOCKING)) {
            $asset->dockingStation = null;
            if (strlen($xml->DOCKING) > 0) {
                $asset->dockingStation = $xml->DOCKING->__toString();
            }
        }
        if (isset($xml->STYLUS)) {
            $asset->stylus = null;
            if (strlen($xml->STYLUS) > 0) {
                $asset->stylus = $xml->STYLUS->__toString();
            }
        }
        if (isset($xml->FIREWIRE)) {
            $asset->firewire = null;
            if (strlen($xml->FIREWIRE) > 0) {
                $asset->firewire = $xml->FIREWIRE->__toString();
            }
        }
        if (isset($xml->KEYBOARD)) {
            $asset->keyboard = null;
            if (strlen($xml->KEYBOARD) > 0) {
                $asset->keyboard = $xml->KEYBOARD->__toString();
            }
        }
        if (isset($xml->MOUSE)) {
            $asset->mouse = null;
            if (strlen($xml->MOUSE) > 0) {
                $asset->mouse = $xml->MOUSE->__toString();
            }
        }
        if (isset($xml->CARTRIDGE)) {
            $asset->cartridge = null;
            if (strlen($xml->CARTRIDGE) > 0) {
                $asset->cartridge = $xml->CARTRIDGE->__toString();
            }
        }
        if (isset($xml->COA)) {
            $asset->coa = null;
            if (strlen($xml->COA) > 0) {
                $asset->coa = $xml->COA->__toString();
            }
        }
        if (isset($xml->OSX_DESCRIPTION)) {
            $asset->osxDescription = null;
            if (strlen($xml->OSX_DESCRIPTION) > 0) {
                $asset->osxDescription = $xml->OSX_DESCRIPTION->__toString();
            }
        }
        if (isset($xml->CONDITION)) {
            $asset->condition = null;
            if (strlen($xml->CONDITION) > 0) {
                $asset->condition = $xml->CONDITION->__toString();
            }
        }
        if (isset($xml->DATE_CODE)) {
            $asset->dateCode = null;
            if (strlen($xml->DATE_CODE) > 0) {
                $asset->dateCode = intval($xml->DATE_CODE->__toString());
            }
        }
        if (isset($xml->COMMENTS)) {
            $asset->comments = null;
            if (strlen($xml->COMMENTS) > 0) {
                $asset->comments = $xml->COMMENTS->__toString();
            }
        }
        if (isset($xml->ADDITIONAL_COMMENTS)) {
            $asset->additionalComments = null;
            if (strlen($xml->ADDITIONAL_COMMENTS) > 0) {
                $asset->additionalComments = $xml->ADDITIONAL_COMMENTS->__toString();
            }
        }
        if (isset($xml->HARD_DRIVE_SERIAL_NUMBER)) {
            $asset->hardDriveSerialNum = null;
            if (strlen($xml->HARD_DRIVE_SERIAL_NUMBER) > 0) {
                $asset->hardDriveSerialNum = $xml->HARD_DRIVE_SERIAL_NUMBER->__toString();
            }
        }
        if (isset($xml->ASSET_TAG)) {
            $asset->assetTag = null;
            if (strlen($xml->ASSET_TAG) > 0) {
                $asset->assetTag = $xml->ASSET_TAG->__toString();
            }
        }
        if (isset($xml->STATUS)) {
            $asset->status = null;
            if (strlen($xml->STATUS) > 0) {
                $asset->status = $xml->STATUS->__toString();
            }
        }
        if (isset($xml->SECURITY_LOCK)) {
            $asset->securityLock = null;
            if (strlen($xml->SECURITY_LOCK) > 0) {
                $asset->securityLock = $xml->SECURITY_LOCK->__toString();
            }
        }
        if (isset($xml->SECURITY_LOCK_RESOLVED)) {
            $asset->securityLockResolved = null;
            if (strlen($xml->SECURITY_LOCK_RESOLVED) > 0) {
                $asset->securityLockResolved = $xml->SECURITY_LOCK_RESOLVED->__toString();
            }
        }
        if (isset($xml->SETTLEMENT_AMOUNT)) {
            $asset->settlementAmount = null;
            if (strlen($xml->SETTLEMENT_AMOUNT) > 0) {
                $asset->settlementAmount = floatval($xml->SETTLEMENT_AMOUNT->__toString());
            }
        }
        if (isset($xml->NET_SETTLEMENT)) {
            $asset->netSettlement = null;
            if (strlen($xml->NET_SETTLEMENT) > 0) {
                $asset->netSettlement = floatval($xml->NET_SETTLEMENT->__toString());
            }
        }
        if (isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) {
            $asset->certOfDataWipeNum = null;
            if (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0) {
                $asset->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
            }
        }
        if (isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) {
            $asset->certOfDestructionNum = null;
            if (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0) {
                $asset->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
            }
        }
        if (isset($xml->HDSN_CONFIG)) {
            $asset->hdsnConfiguration = null;
            if (strlen($xml->HDSN_CONFIG) > 0) {
                $asset->hdsnConfiguration = $xml->HDSN_CONFIG->__toString();
            }
        }

        return $asset;
    }
}