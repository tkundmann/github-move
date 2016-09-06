<?php

namespace App\Data\Models;

use Santigarcor\Laratrust\LaratrustPermission;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Permission
 *
 * @property integer $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Role[] $roles
 */
class Permission extends LaratrustPermission
{
    use Eloquence, Mappable;

    protected $table = 'permission';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'name' => 'name'
        'displayName' => 'display_name',
        // 'description' => 'description'
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
}