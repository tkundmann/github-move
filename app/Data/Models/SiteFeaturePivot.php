<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Data\Models\Site
 *
 * @property integer $site_id
 * @property integer $feature_id
 * @property array $data
 */

class SiteFeaturePivot extends Pivot
{
    public function getDataAttribute($value)
    {
        return unserialize($value);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? serialize($value) : $value;
    }
}
