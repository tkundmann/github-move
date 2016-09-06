<?php

namespace App\Data\Models;

use Santigarcor\Laratrust\LaratrustRole;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data\Models\Permission[] $permissions
 */
class Role extends LaratrustRole
{
    use Eloquence, Mappable;

    const USER        = 'USER';
    const SUPERUSER   = 'SUPERUSER';
    const ADMIN       = 'ADMIN';
    const SUPERADMIN  = 'SUPERADMIN';

    protected $table = 'role';

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