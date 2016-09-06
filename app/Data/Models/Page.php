<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Page
 *
 * @property integer $id
 * @property string $type ('Standard','Certificates of Data Wipe','Certificates of Recycling','Settlements')
 * @property string $name
 * @property string $code
 * @property string $text
 * @property string $description
 * @property integer $siteId
 * @property boolean $userRestricted
 * @property boolean $lotNumberRestricted
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \App\Data\Models\Site $site
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\User[] $users
 */
class Page extends Model
{
    use Eloquence, Mappable, Sortable;

    protected $table = 'page';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'type' => 'type'
        // 'name' => 'name'
        // 'code' => 'code',
        // 'text' => 'text'
        // 'description' => 'description',
        'siteId' => 'site_id',
        'userRestricted' => 'user_restricted',
        'lotNumberRestricted' => 'lot_number_restricted',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function site() {
        return $this->belongsTo('App\Data\Models\Site', 'site_id', 'id');
    }

    public function files() {
        return $this->hasMany('App\Data\Models\File', 'page_id', 'id');
    }
    
    public function users() {
        return $this->belongsToMany('App\Data\Models\User', 'user_page', 'page_id', 'user_id');
    }
}
