<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Santigarcor\Laratrust\Traits\LaratrustUserTrait;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $siteId
 * @property string $rememberToken
 * @property boolean $disabled
 * @property boolean $confirmed
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \App\Data\Models\Site $site
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\LotNumber[] $lotNumbers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\VendorClient[] $vendorClients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Page[] $pages
 */
class User extends Authenticatable
{
    use Eloquence, Mappable, Sortable;
    use LaratrustUserTrait;

    protected $table = 'user';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'name' => 'name'
        // 'email' => 'email'
        // 'password' => 'password'
        'siteId' => 'site_id',
        'rememberToken' => 'remember_token',
        // 'disabled' => 'disabled'
        // 'confirmed' => 'confirmed'
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function site() {
        return $this->belongsTo('App\Data\Models\Site', 'site_id', 'id');
    }

    public function lotNumbers() {
        return $this->belongsToMany('App\Data\Models\LotNumber', 'user_lot_number', 'user_id', 'lot_number_id');
    }

    public function vendorClients() {
        return $this->belongsToMany('App\Data\Models\VendorClient', 'user_vendor_client', 'user_id', 'vendor_client_id');
    }
    
    public function pages() {
        return $this->belongsToMany('App\Data\Models\Page', 'user_page', 'user_id', 'page_id');
    }
}
