<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\LotNumber
 *
 * @property integer $id
 * @property string $prefix
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Site[] $sites
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\File[] $files
 */
class LotNumber extends Model
{
    use Eloquence, Mappable;

    protected $table = 'lot_number';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'prefix' => 'prefix'
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function sites() {
        return $this->belongsToMany('App\Data\Models\Site', 'site_lot_number', 'lot_number_id', 'site_id');
    }

    public function users() {
        return $this->belongsToMany('App\Data\Models\User', 'user_lot_number', 'lot_number_id', 'user_id');
    }

    public function files() {
        return $this->belongsToMany('App\Data\Models\File', 'file_lot_number', 'lot_number_id', 'file_id');
    }
}