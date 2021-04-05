<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Feature
 *
 * @property integer $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property array $data
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Site[] $sites
 */
class Feature extends Model
{
    use Eloquence, Mappable;

    const VENDOR_CLIENT_CODE_ACCESS_RESTRICTED      = 'VENDOR_CLIENT_CODE_ACCESS_RESTRICTED';
    const LOT_NUMBER_PREFIX_ACCESS_RESTRICTED       = 'LOT_NUMBER_PREFIX_ACCESS_RESTRICTED';

    const HIDE_TITLE                                = 'HIDE_TITLE';
    const CENTER_LOGO                               = 'CENTER_LOGO';

    const HAS_PAGES                                 = 'HAS_PAGES';
    const HAS_SETTLEMENTS                           = 'HAS_SETTLEMENTS';
    const HAS_CERTIFICATES                          = 'HAS_CERTIFICATES';
    const HAS_PICKUP_REQUEST                        = 'HAS_PICKUP_REQUEST';

    const IS_WINTHROP                               = 'IS_WINTHROP';        // :)

    const SETTLEMENT_AS_FILE                        = 'SETTLEMENT_AS_FILE';
    const CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE   = 'CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE';
    const CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE = 'CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE';

    const CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER = 'CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER';
    const CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER       = 'CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER';

    const SHIPMENT_CUSTOM_SEARCH_FIELDS             = 'SHIPMENT_CUSTOM_SEARCH_FIELDS';
    const SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS      = 'SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS';
    const SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS      = 'SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS';
    const SHIPMENT_CUSTOM_EXPORT_FIELDS             = 'SHIPMENT_CUSTOM_EXPORT_FIELDS';

    const ASSET_CUSTOM_SEARCH_FIELDS                = 'ASSET_CUSTOM_SEARCH_FIELDS';
    const ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS         = 'ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS';
    const ASSET_CUSTOM_SEARCH_RESULT_FIELDS         = 'ASSET_CUSTOM_SEARCH_RESULT_FIELDS';
    const ASSET_CUSTOM_EXPORT_FIELDS                = 'ASSET_CUSTOM_EXPORT_FIELDS';
    const ASSET_CUSTOM_EMPTY_STATUS                 = 'ASSET_CUSTOM_EMPTY_STATUS';

    const PICKUP_REQUEST_EQUIPMENT_LIST             = 'PICKUP_REQUEST_EQUIPMENT_LIST';
    const PICKUP_REQUEST_ADDRESS_BOOK               = 'PICKUP_REQUEST_ADDRESS_BOOK';

    const PICKUP_REQUEST_SAR_BOX_PROGRAM            = 'PICKUP_REQUEST_SAR_BOX_PROGRAM';

    protected $table = 'feature';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'name' => 'name'
        'displayName' => 'display_name',
        // 'description' => 'description'
        // 'data' => 'data'
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function sites() {
        return $this->belongsToMany('App\Data\Models\Site', 'site_feature', 'feature_id', 'site_id')->withPivot('data');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Site) {
            return new SiteFeaturePivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }

    public function getDataAttribute($value)
    {
        return unserialize($value);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? serialize($value) : $value;
    }
}