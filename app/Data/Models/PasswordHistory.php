<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class PasswordHistory extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo('App\User');
    }
}
