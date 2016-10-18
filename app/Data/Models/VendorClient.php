<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\VendorClient
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Site[] $sites
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\User[] $users
 */
class VendorClient extends Model
{
    use Eloquence, Mappable, Sortable;

    protected $table = 'vendor_client';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'name' => 'name'
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function sites() {
        return $this->belongsToMany('App\Data\Models\Site', 'site_vendor_client', 'vendor_client_id', 'site_id');
    }

    public function users() {
        return $this->belongsToMany('App\Data\Models\User', 'user_lot_number', 'lot_number_id', 'user_id');
    }
}