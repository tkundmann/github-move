<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\File
 *
 * @property integer $id
 * @property string $filename
 * @property string $name
 * @property string $url
 * @property integer $size
 * @property integer $pageId
 * @property integer $shipmentId
 * @property integer $pickupRequestId
 * @property \Carbon\Carbon $fileDate
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \App\Data\Models\Page $page
 * @property-read \App\Data\Models\Shipment $shipment
 * @property-read \App\Data\Models\PickupRequest $pickupRequest
 * @property-read \App\Data\Models\LotNumber[] $lotNumbers
 */
class File extends Model
{
    use Eloquence, Mappable;

    protected $table = 'file';

    protected $dates = [
        'file_date',
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'filename' => 'filename'
        // 'name' => 'name',
        // 'url' => 'url',
        // 'size' => 'size'
        'pageId' => 'page_id',
        'shipmentId' => 'shipment_id',
        'pickupRequestId' => 'pickup_request_id',
        'fileDate' => 'file_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function page() {
        return $this->belongsTo('App\Data\Models\Page', 'page_id', 'id');
    }

    public function shipment() {
        return $this->belongsTo('App\Data\Models\Shipment', 'shipment_id', 'id');
    }

    public function pickupRequest() {
        return $this->belongsTo('App\Data\Models\PickupRequest', 'pickup_request_id', 'id');
    }

    public function lotNumbers() {
        return $this->belongsToMany('App\Data\Models\LotNumber', 'file_lot_number', 'file_id', 'lot_number_id');
    }
}