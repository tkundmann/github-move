<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\PickupRequestAddress
 *
 * @property integer $id
 * @property integer $siteId
 * @property string $name
 * @property string $companyName
 * @property string $companyDivision
 * @property string $contactName
 * @property string $contactPhoneNumber
 * @property string $contactAddress1
 * @property string $contactAddress2
 * @property string $contactCity
 * @property string $contactState
 * @property string $contactZip
 * @property string $contactCountry
 * @property string $contactCellNumber
 * @property string $contactEmailAddress
 * @property boolean $hasDock
 * @property boolean $dockAppointmentRequired
 * @property boolean $unitsLocatedNearDock
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \App\Data\Models\Site $site
 */
class PickupRequestAddress extends Model
{
    use Eloquence, Mappable;

    protected $table = 'pickup_request_address';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        // 'name' => 'name'
        'siteId'                  => 'site_id',
        'companyName'             => 'company_name',
        'companyDivision'         => 'company_division',
        'contactName'             => 'contact_name',
        'contactPhoneNumber'      => 'contact_phone_number',
        'contactAddress1'         => 'contact_address_1',
        'contactAddress2'         => 'contact_address_2',
        'contactCity'             => 'contact_city',
        'contactState'            => 'contact_state',
        'contactZip'              => 'contact_zip',
        'contactCountry'          => 'contact_country',
        'contactCellNumber'       => 'contact_cell_number',
        'contactEmailAddress'     => 'contact_email_address',
        'hasDock'                 => 'has_dock',
        'dockAppointmentRequired' => 'dock_appointment_required',
        'unitsLocatedNearDock'    => 'units_located_near_dock',
        'createdAt'               => 'created_at',
        'updatedAt'               => 'updated_at',
    ];

    public function site() {
        return $this->belongsTo('App\Data\Models\Site', 'site_id', 'id');
    }

}
