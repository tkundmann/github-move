<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Site
 *
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property string $logoUrl
 * @property string $color
 * @property string $type ('Insight','SAR','Other')
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Feature[] $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\VendorClient[] $vendorClients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\LotNumber[] $lotNumbers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Page[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\PickupRequest[] $pickupRequests
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\PickupRequestAddress[] $pickupRequestAddresses
 */
class Site extends Model
{
    use Eloquence, Mappable, Sortable;

    protected $table = 'site';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'code' => 'code'
        // 'title' => 'title'
        'logoUrl' => 'logo_url',
        // 'color' => 'color',
        // 'type' => 'type',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function users() {
        return $this->hasMany('App\Data\Models\User', 'site_id', 'id');
    }

    public function features() {
        return $this->belongsToMany('App\Data\Models\Feature', 'site_feature', 'site_id', 'feature_id')->withPivot('data');
    }

    public function vendorClients() {
        return $this->belongsToMany('App\Data\Models\VendorClient', 'site_vendor_client', 'site_id', 'vendor_client_id');
    }

    public function lotNumbers() {
        return $this->belongsToMany('App\Data\Models\LotNumber', 'site_lot_number', 'site_id', 'lot_number_id');
    }

    public function pages() {
        return $this->hasMany('App\Data\Models\Page', 'site_id', 'id');
    }

    public function pickupRequests() {
        return $this->hasMany('App\Data\Models\PickupRequest', 'site_id', 'id');
    }

    public function pickupRequestAddresses() {
        return $this->hasMany('App\Data\Models\PickupRequestAddress', 'site_id', 'id');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Feature) {
            return new SiteFeaturePivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }

    // --

    public static function doesContextExist($context) {
        return (bool) Site::where('code', '=', $context)->first();
    }

    public static function getSiteByContext($context) {
        return Site::where('code', '=', $context)->first();
    }

    public function hasFeature($featureName) {
        return $this->features->contains('name', $featureName);
    }

    public function getFeature($featureName) {
        return $this->features->where('name', $featureName)->first();
    }

    public function hasPage($pageCode) {
        return $this->pages->contains('code', $pageCode);
    }
    
    public function getPage($pageCode) {
        return $this->pages->where('code', $pageCode)->first();
    }
}
