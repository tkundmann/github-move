<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\PasswordReset
 *
 * @property integer $userId
 * @property string $token
 * @property \Carbon\Carbon $createdAt
 */
class PasswordReset extends Model
{
    use Eloquence, Mappable;
    
    public $timestamps = false;

    protected $table = 'password_reset';
    protected $primaryKey = 'user_id';
    
    protected $dates = [
        'created_at'
    ];
    
    protected $maps = [
        'userId' => 'user_id',
        // 'token' => 'token'
        'createdAt' => 'created_at'
    ];
}
