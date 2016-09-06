<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Shipment
 *
 * @property int $id
 * @property \Carbon\Carbon $lotDate
 * @property \Carbon\Carbon $lotApprovedDate
 * @property string $lotNumber
 * @property string $poNumber
 * @property string $vendorShipmentNumber
 * @property string $costCenter
 * @property string $siteCoordinator
 * @property string $vendor
 * @property string $vendorClient
 * @property string $billOfLading
 * @property string $cityOfOrigin
 * @property \Carbon\Carbon $schedulePickupDate
 * @property string $freightCarrier
 * @property string $freightInvoiceNumber
 * @property float $freightCharge
 * @property \Carbon\Carbon $pickupRequestDate
 * @property string $pickupAddress
 * @property string $pickupAddress2
 * @property string $pickupCity
 * @property string $pickupState
 * @property string $pickupZipCode
 * @property \Carbon\Carbon $actualPickupDate
 * @property \Carbon\Carbon $dateReceived
 * @property \Carbon\Carbon $nfReceivedDate
 * @property string $notaFiscalTransfer
 * @property string $notaFiscalTransfer2
 * @property string $notaFiscalTransfer3
 * @property string $notaFiscalTransfer4
 * @property string $notaFiscalTransfer5
 * @property string $equipmentSummary
 * @property float $totalWeightReceived
 * @property integer $numberOfSkids
 * @property integer $numberOfPieces
 * @property \Carbon\Carbon $preAuditApproved
 * @property \Carbon\Carbon $auditCompleted
 * @property string $certOfDataWipeNum
 * @property string $certOfDestructionNum
 * @property \Carbon\Carbon $importDateTime
 * @property \Carbon\Carbon $updateDateTime
 * @property-read \App\Data\Models\Asset[] $assets
 * @property-read \App\Data\Models\File[] $files
 */
class Shipment extends Model
{
    use Eloquence, Mappable, Sortable;
    
    const CREATED_AT = 'import_date_time';
    const UPDATED_AT = 'update_date_time';
    
    protected $table = 'shipment';

    protected $dates = [
        'lot_date',
        'lot_approved_date',
        'schedule_pickup_date',
        'pickup_request_date',
        'actual_pickup_date',
        'date_received',
        'nf_received_date',
        'pre_audit_approved',
        'audit_completed',
        'import_date_time',
        'update_date_time'
    ];
    protected $maps = [
        // 'id' => 'id'
        'lotDate' => 'lot_date',
        'lotApprovedDate' => 'lot_approved_date',
        'lotNumber' => 'lot_number',
        'poNumber' => 'po_number',
        'vendorShipmentNumber' => 'vendor_shipment_number',
        'costCenter' => 'cost_center',
        'siteCoordinator' => 'site_coordinator',
        // 'vendor' => 'vendor',
        'vendorClient' => 'vendor_client',
        'billOfLading' => 'bill_of_lading',
        'cityOfOrigin' => 'city_of_origin',
        'schedulePickupDate' => 'schedule_pickup_date',
        'freightCarrier' => 'freight_carrier',
        'freightInvoiceNumber' => 'freight_invoice_number',
        'freightCharge' => 'freight_charge',
        'pickupRequestDate' => 'pickup_request_date',
        'pickupAddress' => 'pickup_address',
        'pickupAddress2' => 'pickup_address_2',
        'pickupCity' => 'pickup_city',
        'pickupState' => 'pickup_state',
        'pickupZipCode' => 'pickup_zip_code',
        'actualPickupDate' => 'actual_pickup_date',
        'dateReceived' => 'date_received',
        'nfReceivedDate' => 'nf_received_date',
        'notaFiscalTransfer' => 'nota_fiscal_transfer',
        'notaFiscalTransfer2' => 'nota_fiscal_transfer_2',
        'notaFiscalTransfer3' => 'nota_fiscal_transfer_3',
        'notaFiscalTransfer4' => 'nota_fiscal_transfer_4',
        'notaFiscalTransfer5' => 'nota_fiscal_transfer_5',
        'equipmentSummary' => 'equipment_summary',
        'totalWeightReceived' => 'total_weight_received',
        'numberOfSkids' => 'number_of_skids',
        'numberOfPieces' => 'number_of_pieces',
        'preAuditApproved' => 'pre_audit_approved',
        'auditCompleted' => 'audit_completed',
        'certOfDataWipeNum' => 'cert_of_data_wipe_num',
        'certOfDestructionNum' => 'cert_of_destruction_num',
        'importDateTime' => 'import_date_time',
        'updateDateTime' => 'update_date_time'
    ];

    public function assets()
    {
        return $this->hasMany('App\Data\Models\Asset', 'lot_number', 'lot_number');
    }

    public function files() {
        return $this->hasMany('App\Data\Models\File', 'shipment_id', 'id');
    }

    // --

    static function createFromLotSummary(SimpleXMLElement $xml) {
        $shipment = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if ((isset($xml->LOT_DATE)) && (strlen($xml->LOT_DATE) > 0)) {
            $shipment->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->APPROVAL_DATE)) && (strlen($xml->APPROVAL_DATE) > 0)) {
            $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->attributes()['LOT_NUMBER'])) && (strlen($xml->attributes()['LOT_NUMBER']) > 0)) {
            $shipment->lotNumber = $xml->attributes()->LOT_NUMBER->__toString();
        }
        if ((isset($xml->PO_NUMBER)) && (strlen($xml->PO_NUMBER) > 0)) {
            $shipment->poNumber = $xml->PO_NUMBER->__toString();
        }
        if ((isset($xml->VENDOR_SHIPMENT_NUMBER)) && (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0)) {
            $shipment->vendorShipmentNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
        }
        if ((isset($xml->COST_CENTER)) && (strlen($xml->COST_CENTER) > 0)) {
            $shipment->costCenter = $xml->COST_CENTER->__toString();
        }
        if ((isset($xml->ORIGINATOR)) && (strlen($xml->ORIGINATOR) > 0)) {
            $shipment->siteCoordinator = $xml->ORIGINATOR->__toString();
        }
        if ((isset($xml->VENDOR_NAME)) && (strlen($xml->VENDOR_NAME) > 0)) {
            $shipment->vendor = $xml->VENDOR_NAME->__toString();
        }
        if ((isset($xml->VENDOR_CLIENT)) && (strlen($xml->VENDOR_CLIENT) > 0)) {
            $shipment->vendorClient = $xml->VENDOR_CLIENT->__toString();
        }
        if ((isset($xml->BILL_OF_LADING)) && (strlen($xml->BILL_OF_LADING) > 0)) {
            $shipment->billOfLading = $xml->BILL_OF_LADING->__toString();
        }
        if ((isset($xml->CITY_OF_ORIGIN)) && (strlen($xml->CITY_OF_ORIGIN) > 0)) {
            $shipment->cityOfOrigin = $xml->CITY_OF_ORIGIN->__toString();
        }
        if ((isset($xml->SCHED_PICKUP_DATE)) && (strlen($xml->SCHED_PICKUP_DATE) > 0)) {
            $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->FREIGHT_CARRIER)) && (strlen($xml->FREIGHT_CARRIER) > 0)) {
            $shipment->freightCarrier = $xml->FREIGHT_CARRIER->__toString();
        }
        if ((isset($xml->FREIGHT_INVOICE_NUMBER)) && (strlen($xml->FREIGHT_INVOICE_NUMBER) > 0)) {
            $shipment->freightInvoiceNumber = $xml->FREIGHT_INVOICE_NUMBER->__toString();
        }
        if ((isset($xml->FREIGHT_CHARGE)) && (strlen($xml->FREIGHT_CHARGE) > 0)) {
            $shipment->freightCharge = floatval($xml->FREIGHT_CHARGE->__toString());
        }
        if ((isset($xml->PICKUP_REQUEST_DATE)) && (strlen($xml->PICKUP_REQUEST_DATE) > 0)) {
            $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->PICKUPADDRESS)) && (strlen($xml->PICKUPADDRESS) > 0)) {
            $shipment->pickupAddress = $xml->PICKUPADDRESS->__toString();
        }
        if ((isset($xml->PICKUPADDRESS2)) && (!empty($xml->PICKUPADDRESS2))) {
            $shipment->pickupAddress2 = $xml->PICKUPADDRESS2->__toString();
        }
        if ((isset($xml->PICKUPCITY)) && (strlen($xml->PICKUPCITY) > 0)) {
            $shipment->pickupCity = $xml->PICKUPCITY->__toString();
        }
        if ((isset($xml->PICKUPSTATE)) && (strlen($xml->PICKUPSTATE) > 0)) {
            $shipment->pickupState = $xml->PICKUPSTATE->__toString();
        }
        if ((isset($xml->PICKUPZIPCODE)) && (strlen($xml->PICKUPZIPCODE) > 0)) {
            $shipment->pickupZipCode = $xml->PICKUPZIPCODE->__toString();
        }
        if ((isset($xml->ACTUAL_PICKUP_DATE)) && (strlen($xml->ACTUAL_PICKUP_DATE) > 0)) {
            $shipment->actualPickupDate = DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->DATE_RECEIVED)) && (strlen($xml->DATE_RECEIVED) > 0)) {
            $shipment->dateReceived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->NF_RECEIVED_DATE )) && (strlen($xml->NF_RECEIVED_DATE) > 0)) {
            $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER)) && (strlen($xml->NOTA_FISCAL_TRANSFER) > 0)) {
            $shipment->notaFiscalTransfer = $xml->NOTA_FISCAL_TRANSFER->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER2)) && (!empty($xml->NOTA_FISCAL_TRANSFER2))) {
            $shipment->notaFiscalTransfer2 = $xml->NOTA_FISCAL_TRANSFER2->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER3)) && (!empty($xml->NOTA_FISCAL_TRANSFER3))) {
            $shipment->notaFiscalTransfer3 = $xml->NOTA_FISCAL_TRANSFER3->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER4)) && (!empty($xml->NOTA_FISCAL_TRANSFER4))) {
            $shipment->notaFiscalTransfer4 = $xml->NOTA_FISCAL_TRANSFER4->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER5)) && (!empty($xml->NOTA_FISCAL_TRANSFER5))) {
            $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER5)) && (!empty($xml->NOTA_FISCAL_TRANSFER5))) {
            $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
        }
        if ((isset($xml->EQUIPMENT_SUMMARY)) && (strlen($xml->EQUIPMENT_SUMMARY) > 0)) {
            $shipment->equipmentSummary = $xml->EQUIPMENT_SUMMARY->__toString();
        }
        if ((isset($xml->TOTAL_WEIGHT_RECEIVED)) && (strlen($xml->TOTAL_WEIGHT_RECEIVED) > 0)) {
            $shipment->totalWeightReceived = floatval($xml->TOTAL_WEIGHT_RECEIVED->__toString());
        }
        if ((isset($xml->NUMBER_OF_SKIDS)) && (strlen($xml->NUMBER_OF_SKIDS) > 0)) {
            $shipment->numberOfSkids = intval($xml->NUMBER_OF_SKIDS->__toString());
        }
        if ((isset($xml->NUMBER_OF_PIECES)) && (strlen($xml->NUMBER_OF_PIECES) > 0)) {
            $shipment->numberOfPieces = intval($xml->NUMBER_OF_PIECES->__toString());
        }
        if ((isset($xml->PRE_AUDIT_APPROVED_DATE)) && (strlen($xml->PRE_AUDIT_APPROVED_DATE) > 0)) {
            $shipment->preAuditApproved = DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->AUDIT_COMPLETED)) && (strlen($xml->AUDIT_COMPLETED) > 0)) {
            $shipment->auditCompleted = DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0)) {
            $shipment->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
        }
        if ((isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0)) {
            $shipment->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
        }

        return $shipment;
    }
    
    static function createFromLotControl(SimpleXMLElement $xml) {
        $shipment = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if ((isset($xml->LOT_DATE)) && (strlen($xml->LOT_DATE) > 0)) {
            $shipment->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->APPROVAL_DATE)) && (strlen($xml->APPROVAL_DATE) > 0)) {
            $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->attributes()['LOT_NUMBER'])) && (strlen($xml->attributes()['LOT_NUMBER']) > 0)) {
            $shipment->lotNumber = $xml->attributes()->LOT_NUMBER->__toString();
        }
        if ((isset($xml->PO_NUMBER)) && (strlen($xml->PO_NUMBER) > 0)) {
            $shipment->poNumber = $xml->PO_NUMBER->__toString();
        }
        if ((isset($xml->VENDOR_SHIPMENT_NUMBER)) && (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0)) {
            $shipment->vendorShipmentNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
        }
        if ((isset($xml->COST_CENTER)) && (strlen($xml->COST_CENTER) > 0)) {
            $shipment->costCenter = $xml->COST_CENTER->__toString();
        }
        if ((isset($xml->ORIGINATOR)) && (strlen($xml->ORIGINATOR) > 0)) {
            $shipment->siteCoordinator = $xml->ORIGINATOR->__toString();
        }
        if ((isset($xml->VENDOR_NAME)) && (strlen($xml->VENDOR_NAME) > 0)) {
            $shipment->vendor = $xml->VENDOR_NAME->__toString();
        }
        if ((isset($xml->VENDOR_CLIENT)) && (strlen($xml->VENDOR_CLIENT) > 0)) {
            $shipment->vendorClient = $xml->VENDOR_CLIENT->__toString();
        }
        if ((isset($xml->BILL_OF_LADING)) && (strlen($xml->BILL_OF_LADING) > 0)) {
            $shipment->billOfLading = $xml->BILL_OF_LADING->__toString();
        }
        if ((isset($xml->CITY_OF_ORIGIN)) && (strlen($xml->CITY_OF_ORIGIN) > 0)) {
            $shipment->cityOfOrigin = $xml->CITY_OF_ORIGIN->__toString();
        }
        if ((isset($xml->SCHED_PICKUP_DATE)) && (strlen($xml->SCHED_PICKUP_DATE) > 0)) {
            $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->FREIGHT_CARRIER)) && (strlen($xml->FREIGHT_CARRIER) > 0)) {
            $shipment->freightCarrier = $xml->FREIGHT_CARRIER->__toString();
        }
        if ((isset($xml->FREIGHT_INVOICE_NUMBER)) && (strlen($xml->FREIGHT_INVOICE_NUMBER) > 0)) {
            $shipment->freightInvoiceNumber = $xml->FREIGHT_INVOICE_NUMBER->__toString();
        }
        if ((isset($xml->FREIGHT_CHARGE)) && (strlen($xml->FREIGHT_CHARGE) > 0)) {
            $shipment->freightCharge = floatval($xml->FREIGHT_CHARGE->__toString());
        }
        if ((isset($xml->PICKUP_REQUEST_DATE)) && (strlen($xml->PICKUP_REQUEST_DATE) > 0)) {
            $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->ACTUAL_PICKUP_DATE)) && (strlen($xml->ACTUAL_PICKUP_DATE) > 0)) {
            $shipment->actualPickupDate = DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->DATE_RECEIVED)) && (strlen($xml->DATE_RECEIVED) > 0)) {
            $shipment->dateReceived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->NF_RECEIVED_DATE )) && (strlen($xml->NF_RECEIVED_DATE) > 0)) {
            $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER)) && (strlen($xml->NOTA_FISCAL_TRANSFER) > 0)) {
            $shipment->notaFiscalTransfer = $xml->NOTA_FISCAL_TRANSFER->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER2)) && (!empty($xml->NOTA_FISCAL_TRANSFER2))) {
            $shipment->notaFiscalTransfer2 = $xml->NOTA_FISCAL_TRANSFER2->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER3)) && (!empty($xml->NOTA_FISCAL_TRANSFER3))) {
            $shipment->notaFiscalTransfer3 = $xml->NOTA_FISCAL_TRANSFER3->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER4)) && (!empty($xml->NOTA_FISCAL_TRANSFER4))) {
            $shipment->notaFiscalTransfer4 = $xml->NOTA_FISCAL_TRANSFER4->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER5)) && (!empty($xml->NOTA_FISCAL_TRANSFER5))) {
            $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
        }
        if ((isset($xml->NOTA_FISCAL_TRANSFER5)) && (!empty($xml->NOTA_FISCAL_TRANSFER5))) {
            $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
        }
        if ((isset($xml->EQUIPMENT_SUMMARY)) && (strlen($xml->EQUIPMENT_SUMMARY) > 0)) {
            $shipment->equipmentSummary = $xml->EQUIPMENT_SUMMARY->__toString();
        }
        if ((isset($xml->TOTAL_WEIGHT_RECEIVED)) && (strlen($xml->TOTAL_WEIGHT_RECEIVED) > 0)) {
            $shipment->totalWeightReceived = floatval($xml->TOTAL_WEIGHT_RECEIVED->__toString());
        }
        if ((isset($xml->NUMBER_OF_SKIDS)) && (strlen($xml->NUMBER_OF_SKIDS) > 0)) {
            $shipment->numberOfSkids = intval($xml->NUMBER_OF_SKIDS->__toString());
        }
        if ((isset($xml->NUMBER_OF_PIECES)) && (strlen($xml->NUMBER_OF_PIECES) > 0)) {
            $shipment->numberOfPieces = intval($xml->NUMBER_OF_PIECES->__toString());
        }
        if ((isset($xml->PRE_AUDIT_APPROVED_DATE)) && (strlen($xml->PRE_AUDIT_APPROVED_DATE) > 0)) {
            $shipment->preAuditApproved = DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->AUDIT_COMPLETED)) && (strlen($xml->AUDIT_COMPLETED) > 0)) {
            $shipment->auditCompleted = DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
        }
        if ((isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0)) {
            $shipment->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
        }
        if ((isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) && (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0)) {
            $shipment->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
        }

        return $shipment;
    }
}