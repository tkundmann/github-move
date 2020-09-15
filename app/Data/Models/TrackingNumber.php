<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Tracking
 *
 * @property int $id
 * @property int $entryNumber
 * @property string $lotNumber
 * @property string $packageTrackingNumber
 * @property string $packageTrackingType
 * @property string $shippingAgentCode
 * @property string $scanned
 * @property \Carbon\Carbon $scannedDate
 * @property string $scannedTime
 * @property string $trackingNumberURL
 */
class TrackingNumber extends Model
{
    use Eloquence, Mappable, Sortable;

    const CREATED_AT = 'import_date_time';
    const UPDATED_AT = 'update_date_time';

    protected $table = 'trackingnumber';

    protected $dates = [
        'scanned_date'
    ];
    protected $maps = [
        'entryNumber'           => 'entry_number',
        'lotNumber'             => 'lot_number',
        'packageTrackingNumber' => 'package_tracking_number',
        'packageTrackingType'   => 'package_tracking_type',
        'shippingAgentCode'     => 'shipping_agent_code',
        //'scanned'               => 'scanned',
        'scannedDate'           => 'scanned_date',
        'scannedTime'           => 'scanned_time',
        'trackingNumberURL'     => 'tracking_number_url'
    ];

    public function shipment()
    {
        return $this->belongsTo('App\Data\Models\Shipment', 'lot_number', 'lot_number');
    }

    // --

    static function createFromTrackingDetail(SimpleXMLElement $trackingDetail) {
        $trackingNumber = new self();

        if (isset($trackingDetail->ENTRY_NO)) {
            $trackingNumber->entryNumber = null;
            if (strlen($trackingDetail->ENTRY_NO) > 0) {
                $trackingNumber->entryNumber = intval($trackingDetail->ENTRY_NO->__toString());
            }
        }

        if (isset($trackingDetail->LOT_NO)) {
            $trackingNumber->lotNumber = null;
            if (strlen($trackingDetail->LOT_NO) > 0) {
                $trackingNumber->lotNumber = $trackingDetail->LOT_NO->__toString();
            }
        }

        if (isset($trackingDetail->TRACKING_NO)) {
            $trackingNumber->packageTrackingNumber = null;
            if (strlen($trackingDetail->TRACKING_NO) > 0) {
                $trackingNumber->packageTrackingNumber = $trackingDetail->TRACKING_NO->__toString();
            }
        }

        if (isset($trackingDetail->TYPE)) {
            $trackingNumber->packageTrackingType = null;
            if (strlen($trackingDetail->TYPE) > 0) {
                $trackingNumber->packageTrackingType = $trackingDetail->TYPE->__toString();
            }
        }

        if (isset($trackingDetail->SHIP_AGENT)) {
            $trackingNumber->shippingAgentCode = null;
            if (strlen($trackingDetail->SHIP_AGENT) > 0) {
                $trackingNumber->shippingAgentCode = $trackingDetail->SHIP_AGENT->__toString();
            }
        }

        if (isset($trackingDetail->SCANNED)) {
            $trackingNumber->scanned = null;
            if (strlen($trackingDetail->SCANNED) > 0) {
                $trackingNumber->scanned = $trackingDetail->SCANNED->__toString();
            }
        }

        if (isset($trackingDetail->SCAN_DATE)) {
            $trackingNumber->scannedDate = null;
            if (strlen($trackingDetail->SCAN_DATE) > 0) {
                if (DateTime::createFromFormat('m/d/y', $trackingDetail->SCAN_DATE->__toString())) {
                    $trackingNumber->scannedDate = DateTime::createFromFormat('m/d/y', $trackingDetail->SCAN_DATE->__toString())->setTime(0, 0, 0);
                }
                else if (DateTime::createFromFormat('m/d/Y', $trackingDetail->SCAN_DATE->__toString())) {
                    $trackingNumber->scannedDate = DateTime::createFromFormat('m/d/Y', $trackingDetail->SCAN_DATE->__toString())->setTime(0, 0, 0);
                }
            }
        }

        if (isset($trackingDetail->SCAN_TIME)) {
            $trackingNumber->scannedTime = null;
            if (strlen($trackingDetail->SCAN_TIME) > 0) {
                $trackingNumber->scannedTime = $trackingDetail->SCAN_TIME->__toString();
            }
        }

        if (isset($trackingDetail->URL)) {
            $trackingNumber->trackingNumberURL = null;
            if (strlen($trackingDetail->URL) > 0) {
                $trackingNumber->trackingNumberURL = $trackingDetail->URL->__toString();
            }
        }

        return $trackingNumber;
    }

}