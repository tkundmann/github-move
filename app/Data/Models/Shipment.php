<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Log;

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
 * @property string $representative
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
        //'representative' => 'representative'
    ];

    public function assets()
    {
        return $this->hasMany('App\Data\Models\Asset', 'lot_number', 'lot_number');
    }

    public function files() {
        return $this->hasMany('App\Data\Models\File', 'shipment_id', 'id');
    }

    // --

    public static function forLotNumberAndSiteId($lotNumber, $siteId) {

        $columns = array('shipment.*');

        return self::join('vendor_client', 'vendor_client.name', '=', 'shipment.vendor_client')
            ->join('site_vendor_client', 'vendor_client.id', '=', 'site_vendor_client.vendor_client_id')
            ->join('site', 'site_vendor_client.site_id', '=', 'site.id')
            ->where([['shipment.lot_number', '=', $lotNumber],['site.id', '=', $siteId]])->get($columns)->first();
    }

    static function createFromLotSummary(SimpleXMLElement $xml) {
        $shipment = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if (isset($xml->LOT_DATE)) {
            $shipment->lotDate = null;
            if (strlen($xml->LOT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())) {
                    $shipment->lotDate = DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())) {
                    $shipment->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->APPROVAL_DATE)) {
            $shipment->lotApprovedDate = null;
            if (strlen($xml->APPROVAL_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->APPROVAL_DATE->__toString())) {
                    $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/Y', $xml->APPROVAL_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->APPROVAL_DATE->__toString())) {
                    $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/y', $xml->APPROVAL_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->attributes()['LOT_NUMBER'])) {
            $shipment->lotNumber = null;
            if (strlen($xml->attributes()['LOT_NUMBER']) > 0) {
                $shipment->lotNumber = $xml->attributes()->LOT_NUMBER->__toString();
            }
        }

        if (isset($xml->PO_NUMBER)) {
            $shipment->poNumber = null;
            if (strlen($xml->PO_NUMBER) > 0) {
                $shipment->poNumber = $xml->PO_NUMBER->__toString();
            }
        }

        if (isset($xml->VENDOR_SHIPMENT_NUMBER)) {
            $shipment->vendorShipmentNumber = null;
            if (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0) {
                $shipment->vendorShipmentNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
            }
        }

        if (isset($xml->COST_CENTER)) {
            $shipment->costCenter = null;
            if (strlen($xml->COST_CENTER) > 0) {
                $shipment->costCenter = $xml->COST_CENTER->__toString();
            }
        }

        if (isset($xml->ORIGINATOR)) {
            $shipment->siteCoordinator = null;
            if (strlen($xml->ORIGINATOR) > 0) {
                $shipment->siteCoordinator = $xml->ORIGINATOR->__toString();
            }
        }

        if (isset($xml->VENDOR_NAME)) {
            $shipment->vendor = null;
            if (strlen($xml->VENDOR_NAME) > 0) {
                $shipment->vendor = $xml->VENDOR_NAME->__toString();
            }
        }

        if (isset($xml->VENDOR_CLIENT)) {
            $shipment->vendorClient = null;
            if (strlen($xml->VENDOR_CLIENT) > 0) {
                $shipment->vendorClient = $xml->VENDOR_CLIENT->__toString();
            }
        }

        if (isset($xml->BILL_OF_LADING)) {
            $shipment->billOfLading = null;
            if (strlen($xml->BILL_OF_LADING) > 0) {
                $shipment->billOfLading = $xml->BILL_OF_LADING->__toString();
            }
        }

        if (isset($xml->CITY_OF_ORIGIN)) {
            $shipment->cityOfOrigin = null;
            if (strlen($xml->CITY_OF_ORIGIN) > 0) {
                $shipment->cityOfOrigin = $xml->CITY_OF_ORIGIN->__toString();
            }
        }

        if (isset($xml->SCHED_PICKUP_DATE)) {
            $shipment->schedulePickupDate = null;
            if (strlen($xml->SCHED_PICKUP_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->SCHED_PICKUP_DATE->__toString())) {
                    $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/Y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())) {
                    $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->FREIGHT_CARRIER)) {
            $shipment->freightCarrier = null;
            if (strlen($xml->FREIGHT_CARRIER) > 0) {
                $shipment->freightCarrier = $xml->FREIGHT_CARRIER->__toString();
            }
        }

        if (isset($xml->FREIGHT_INVOICE_NUMBER)) {
            $shipment->freightInvoiceNumber = null;
            if (strlen($xml->FREIGHT_INVOICE_NUMBER) > 0) {
                $shipment->freightInvoiceNumber = $xml->FREIGHT_INVOICE_NUMBER->__toString();
            }
        }

        if (isset($xml->FREIGHT_CHARGE)) {
            $shipment->freightCharge = null;
            if (strlen($xml->FREIGHT_CHARGE) > 0) {
                $shipment->freightCharge = floatval($xml->FREIGHT_CHARGE->__toString());
            }
        }

        if (isset($xml->PICKUP_REQUEST_DATE)) {
            $shipment->pickupRequestDate = null;
            if (strlen($xml->PICKUP_REQUEST_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->PICKUP_REQUEST_DATE->__toString())) {
                    $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/Y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())) {
                    $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->PICKUPADDRESS)) {
            $shipment->pickupAddress = null;
            if (strlen($xml->PICKUPADDRESS) > 0) {
                $shipment->pickupAddress = $xml->PICKUPADDRESS->__toString();
            }
        }

        if (isset($xml->PICKUPADDRESS2)) {
            $shipment->pickupAddress2 = null;
            if (!empty($xml->PICKUPADDRESS2)) {
                $shipment->pickupAddress2 = $xml->PICKUPADDRESS2->__toString();
            }
        }

        if (isset($xml->PICKUPCITY)) {
            $shipment->pickupCity = null;
            if (strlen($xml->PICKUPCITY) > 0) {
                $shipment->pickupCity = $xml->PICKUPCITY->__toString();
            }
        }

        if (isset($xml->PICKUPSTATE)) {
            $shipment->pickupState = null;
            if (strlen($xml->PICKUPSTATE) > 0) {
                $shipment->pickupState = $xml->PICKUPSTATE->__toString();
            }
        }

        if (isset($xml->PICKUPZIPCODE)) {
            $shipment->pickupZipCode = null;
            if (strlen($xml->PICKUPZIPCODE) > 0) {
                $shipment->pickupZipCode = $xml->PICKUPZIPCODE->__toString();
            }
        }

        if (isset($xml->ACTUAL_PICKUP_DATE)) {
            $shipment->actualPickupDate = null;
            if (strlen($xml->ACTUAL_PICKUP_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->ACTUAL_PICKUP_DATE->__toString())) {
                    $shipment->actualPickupDate = DateTime::createFromFormat('m/d/Y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())) {
                    $shipment->actualPickupDate = DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->DATE_RECEIVED)) {
            $shipment->dateReceived = null;
            if (strlen($xml->DATE_RECEIVED) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())) {
                    $shipment->dateReceived = DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())) {
                    $shipment->dateReceived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->NF_RECEIVED_DATE )) {
            $shipment->nfReceivedDate = null;
            if (strlen($xml->NF_RECEIVED_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->NF_RECEIVED_DATE->__toString())) {
                    $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/Y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())) {
                    $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER)) {
            $shipment->notaFiscalTransfer = null;
            if (strlen($xml->NOTA_FISCAL_TRANSFER) > 0) {
                $shipment->notaFiscalTransfer = $xml->NOTA_FISCAL_TRANSFER->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER2)) {
            $shipment->notaFiscalTransfer2 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER2)) {
                $shipment->notaFiscalTransfer2 = $xml->NOTA_FISCAL_TRANSFER2->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER3)) {
            $shipment->notaFiscalTransfer3 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER3)) {
                $shipment->notaFiscalTransfer3 = $xml->NOTA_FISCAL_TRANSFER3->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER4)) {
            $shipment->notaFiscalTransfer4 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER4)) {
                $shipment->notaFiscalTransfer4 = $xml->NOTA_FISCAL_TRANSFER4->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER5)) {
            $shipment->notaFiscalTransfer5 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER5)) {
                $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER5)) {
            $shipment->notaFiscalTransfer5 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER5)) {
                $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
            }
        }

        if (isset($xml->EQUIPMENT_SUMMARY)) {
            $shipment->equipmentSummary = null;
            if (strlen($xml->EQUIPMENT_SUMMARY) > 0) {
                $shipment->equipmentSummary = $xml->EQUIPMENT_SUMMARY->__toString();
            }
        }

        if (isset($xml->TOTAL_WEIGHT_RECEIVED)) {
            $shipment->totalWeightReceived = null;
            if (strlen($xml->TOTAL_WEIGHT_RECEIVED) > 0) {
                $shipment->totalWeightReceived = floatval($xml->TOTAL_WEIGHT_RECEIVED->__toString());
            }
        }

        if (isset($xml->NUMBER_OF_SKIDS)) {
            $shipment->numberOfSkids = null;
            if (strlen($xml->NUMBER_OF_SKIDS) > 0) {
                $shipment->numberOfSkids = intval($xml->NUMBER_OF_SKIDS->__toString());
            }
        }

        if (isset($xml->NUMBER_OF_PIECES)) {
            $shipment->numberOfPieces = null;
            if (strlen($xml->NUMBER_OF_PIECES) > 0) {
                $shipment->numberOfPieces = intval($xml->NUMBER_OF_PIECES->__toString());
            }
        }

        if (isset($xml->PRE_AUDIT_APPROVED_DATE)) {
            $shipment->preAuditApproved = null;
            if (strlen($xml->PRE_AUDIT_APPROVED_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())) {
                    $shipment->preAuditApproved = DateTime::createFromFormat('m/d/Y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())) {
                    $shipment->preAuditApproved = DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->AUDIT_COMPLETED)) {
            $shipment->auditCompleted = null;
            if (strlen($xml->AUDIT_COMPLETED) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->AUDIT_COMPLETED->__toString())) {
                    $shipment->auditCompleted = DateTime::createFromFormat('m/d/Y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())) {
                    $shipment->auditCompleted = DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) {
            $shipment->certOfDataWipeNum = null;
            if (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0) {
                $shipment->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
            }
        }

        if (isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) {
            $shipment->certOfDestructionNum = null;
            if (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0) {
                $shipment->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
            }
        }

        if (isset($xml->REPRESENTATIVE)) {
            $shipment->representative = null;
            if (strlen($xml->REPRESENTATIVE) > 0) {
                $shipment->representative = $xml->REPRESENTATIVE->__toString();
            }
        }

        return $shipment;
    }

    static function createFromLotControl(SimpleXMLElement $xml) {
        $shipment = new self();

        // DATE_TIME_STAMP in XML has 'm/d/y h:i A' format, but apparently not needed

        if (isset($xml->LOT_DATE)) {
            $shipment->lotDate = null;
            if (strlen($xml->LOT_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())) {
                    $shipment->lotDate = DateTime::createFromFormat('m/d/Y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())) {
                    $shipment->lotDate = DateTime::createFromFormat('m/d/y', $xml->LOT_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->APPROVAL_DATE)) {
            $shipment->lotApprovedDate = null;
            if (strlen($xml->APPROVAL_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->APPROVAL_DATE->__toString())) {
                    $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/Y', $xml->APPROVAL_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->APPROVAL_DATE->__toString())) {
                    $shipment->lotApprovedDate = DateTime::createFromFormat('m/d/y', $xml->APPROVAL_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->attributes()['LOT_NUMBER'])) {
            $shipment->lotNumber = null;
            if (strlen($xml->attributes()['LOT_NUMBER']) > 0) {
                $shipment->lotNumber = $xml->attributes()->LOT_NUMBER->__toString();
            }
        }

        if (isset($xml->PO_NUMBER)) {
            $shipment->poNumber = null;
            if (strlen($xml->PO_NUMBER) > 0) {
                $shipment->poNumber = $xml->PO_NUMBER->__toString();
            }
        }

        if (isset($xml->VENDOR_SHIPMENT_NUMBER)) {
            $shipment->vendorShipmentNumber = null;
            if (strlen($xml->VENDOR_SHIPMENT_NUMBER) > 0) {
                $shipment->vendorShipmentNumber = $xml->VENDOR_SHIPMENT_NUMBER->__toString();
            }
        }

        if (isset($xml->COST_CENTER)) {
            $shipment->costCenter = null;
            if (strlen($xml->COST_CENTER) > 0) {
                $shipment->costCenter = $xml->COST_CENTER->__toString();
            }
        }

        if (isset($xml->ORIGINATOR)) {
            $shipment->siteCoordinator = null;
            if (strlen($xml->ORIGINATOR) > 0) {
                $shipment->siteCoordinator = $xml->ORIGINATOR->__toString();
            }
        }

        if (isset($xml->VENDOR_NAME)) {
            $shipment->vendor = null;
            if (strlen($xml->VENDOR_NAME) > 0) {
                $shipment->vendor = $xml->VENDOR_NAME->__toString();
            }
        }

        if (isset($xml->VENDOR_CLIENT)) {
            $shipment->vendorClient = null;
            if (strlen($xml->VENDOR_CLIENT) > 0) {
                $shipment->vendorClient = $xml->VENDOR_CLIENT->__toString();
            }
        }

        if (isset($xml->BILL_OF_LADING)) {
            $shipment->billOfLading = null;
            if (strlen($xml->BILL_OF_LADING) > 0) {
                $shipment->billOfLading = $xml->BILL_OF_LADING->__toString();
            }
        }

        if (isset($xml->CITY_OF_ORIGIN)) {
            $shipment->cityOfOrigin = null;
            if (strlen($xml->CITY_OF_ORIGIN) > 0) {
                $shipment->cityOfOrigin = $xml->CITY_OF_ORIGIN->__toString();
            }
        }

        if (isset($xml->SCHED_PICKUP_DATE)) {
            $shipment->schedulePickupDate = null;
            if (strlen($xml->SCHED_PICKUP_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->SCHED_PICKUP_DATE->__toString())) {
                    $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/Y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())) {
                    $shipment->schedulePickupDate = DateTime::createFromFormat('m/d/y', $xml->SCHED_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->FREIGHT_CARRIER)) {
            $shipment->freightCarrier = null;
            if (strlen($xml->FREIGHT_CARRIER) > 0) {
                $shipment->freightCarrier = $xml->FREIGHT_CARRIER->__toString();
            }
        }

        if (isset($xml->FREIGHT_INVOICE_NUMBER)) {
            $shipment->freightInvoiceNumber = null;
            if (strlen($xml->FREIGHT_INVOICE_NUMBER) > 0) {
                $shipment->freightInvoiceNumber = $xml->FREIGHT_INVOICE_NUMBER->__toString();
            }
        }

        if (isset($xml->FREIGHT_CHARGE)) {
            $shipment->freightCharge = null;
            if (strlen($xml->FREIGHT_CHARGE) > 0) {
                $shipment->freightCharge = floatval($xml->FREIGHT_CHARGE->__toString());
            }
        }

        if (isset($xml->PICKUP_REQUEST_DATE)) {
            $shipment->pickupRequestDate = null;
            if (strlen($xml->PICKUP_REQUEST_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->PICKUP_REQUEST_DATE->__toString())) {
                    $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/Y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())) {
                    $shipment->pickupRequestDate = DateTime::createFromFormat('m/d/y', $xml->PICKUP_REQUEST_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->ACTUAL_PICKUP_DATE)) {
            $shipment->actualPickupDate = null;
            if (strlen($xml->ACTUAL_PICKUP_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->ACTUAL_PICKUP_DATE->__toString())) {
                    $shipment->actualPickupDate = DateTime::createFromFormat('m/d/Y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())) {
                    $shipment->actualPickupDate = DateTime::createFromFormat('m/d/y', $xml->ACTUAL_PICKUP_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->DATE_RECEIVED)) {
            $shipment->dateReceived = null;
            if (strlen($xml->DATE_RECEIVED) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())) {
                    $shipment->dateReceived = DateTime::createFromFormat('m/d/Y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())) {
                    $shipment->dateReceived = DateTime::createFromFormat('m/d/y', $xml->DATE_RECEIVED->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->NF_RECEIVED_DATE)) {
            $shipment->nfReceivedDate = null;
            if (strlen($xml->NF_RECEIVED_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->NF_RECEIVED_DATE->__toString())) {
                    $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/Y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())) {
                    $shipment->nfReceivedDate = DateTime::createFromFormat('m/d/y', $xml->NF_RECEIVED_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER)) {
            $shipment->notaFiscalTransfer = null;
            if (strlen($xml->NOTA_FISCAL_TRANSFER) > 0) {
                $shipment->notaFiscalTransfer = $xml->NOTA_FISCAL_TRANSFER->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER2)) {
            $shipment->notaFiscalTransfer2 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER2)) {
                $shipment->notaFiscalTransfer2 = $xml->NOTA_FISCAL_TRANSFER2->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER3)) {
            $shipment->notaFiscalTransfer3 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER3)) {
                $shipment->notaFiscalTransfer3 = $xml->NOTA_FISCAL_TRANSFER3->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER4)) {
            $shipment->notaFiscalTransfer4 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER4)) {
                $shipment->notaFiscalTransfer4 = $xml->NOTA_FISCAL_TRANSFER4->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER5)) {
            $shipment->notaFiscalTransfer5 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER5)) {
                $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
            }
        }

        if (isset($xml->NOTA_FISCAL_TRANSFER5)) {
            $shipment->notaFiscalTransfer5 = null;
            if (!empty($xml->NOTA_FISCAL_TRANSFER5)) {
                $shipment->notaFiscalTransfer5 = $xml->NOTA_FISCAL_TRANSFER5->__toString();
            }
        }

        if (isset($xml->EQUIPMENT_SUMMARY)) {
            $shipment->equipmentSummary = null;
            if (strlen($xml->EQUIPMENT_SUMMARY) > 0) {
                $shipment->equipmentSummary = $xml->EQUIPMENT_SUMMARY->__toString();
            }
        }

        if (isset($xml->TOTAL_WEIGHT_RECEIVED)) {
            $shipment->totalWeightReceived = null;
            if (strlen($xml->TOTAL_WEIGHT_RECEIVED) > 0) {
                $shipment->totalWeightReceived = floatval($xml->TOTAL_WEIGHT_RECEIVED->__toString());
            }
        }

        if (isset($xml->NUMBER_OF_SKIDS)) {
            $shipment->numberOfSkids = null;
            if (strlen($xml->NUMBER_OF_SKIDS) > 0) {
                $shipment->numberOfSkids = intval($xml->NUMBER_OF_SKIDS->__toString());
            }
        }

        if (isset($xml->NUMBER_OF_PIECES)) {
            $shipment->numberOfPieces = null;
            if (strlen($xml->NUMBER_OF_PIECES) > 0) {
                $shipment->numberOfPieces = intval($xml->NUMBER_OF_PIECES->__toString());
            }
        }

        if (isset($xml->PRE_AUDIT_APPROVED_DATE)) {
            $shipment->preAuditApproved = null;
            if (strlen($xml->PRE_AUDIT_APPROVED_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())) {
                    $shipment->preAuditApproved = DateTime::createFromFormat('m/d/Y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())) {
                    $shipment->preAuditApproved = DateTime::createFromFormat('m/d/y', $xml->PRE_AUDIT_APPROVED_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->AUDIT_COMPLETED)) {
            $shipment->auditCompleted = null;
            if (strlen($xml->AUDIT_COMPLETED) > 0) {
                if (DateTime::createFromFormat('m/d/Y', $xml->AUDIT_COMPLETED->__toString())) {
                    $shipment->auditCompleted = DateTime::createFromFormat('m/d/Y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())) {
                    $shipment->auditCompleted = DateTime::createFromFormat('m/d/y', $xml->AUDIT_COMPLETED->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER)) {
            $shipment->certOfDataWipeNum = null;
            if (strlen($xml->CERTIFICATE_OF_DATA_WIPE_NUMBER) > 0) {
                $shipment->certOfDataWipeNum = $xml->CERTIFICATE_OF_DATA_WIPE_NUMBER->__toString();
            }
        }

        if (isset($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER)) {
            $shipment->certOfDestructionNum = null;
            if (strlen($xml->CERTIFICATE_OF_DESTRUCTION_NUMBER) > 0) {
                $shipment->certOfDestructionNum = $xml->CERTIFICATE_OF_DESTRUCTION_NUMBER->__toString();
            }
        }

        if (isset($xml->REPRESENTATIVE)) {
            $shipment->representative = null;
            if (strlen($xml->REPRESENTATIVE) > 0) {
                $shipment->representative = $xml->REPRESENTATIVE->__toString();
            }
        }

        return $shipment;
    }
}