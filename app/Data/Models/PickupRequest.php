<?php

namespace App\Data\Models;

use App\Extensions\Eloquent\Sortable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Data\Models\PickupRequest
 *
 * @property integer $id
 * @property integer $siteId
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
 * @property string $referenceNumber
 * @property integer $numInternalHardDrives
 * @property integer $numDesktops
 * @property integer $numLaptops
 * @property integer $numMonitors
 * @property integer $numCRTMonitors
 * @property integer $numLCDMonitors
 * @property integer $numPrinters
 * @property integer $numServers
 * @property integer $numNetworking
 * @property integer $numStorageSystems
 * @property integer $numUps
 * @property integer $numRacks
 * @property integer $numOther
 * @property integer $numMisc
 * @property integer $totalNumAssets
 * @property boolean $internalHardDriveEncrypted
 * @property boolean $internalHardDriveWiped
 * @property boolean $desktopEncrypted
 * @property boolean $desktopHardDriveWiped
 * @property boolean $laptopEncrypted
 * @property boolean $laptopHardDriveWiped
 * @property boolean $serverEncrypted
 * @property boolean $serverHardDriveWiped
 * @property \Carbon\Carbon $preferredPickupDate
 * @property string $preferredPickupDateInformation
 * @property boolean $unitsLocatedNearDock
 * @property boolean $unitsOnSingleFloor
 * @property boolean $isLiftGateNeeded
 * @property boolean $isLoadingDockPresent
 * @property boolean $dockAppointmentRequired
 * @property boolean $assetsNeedPackaging
 * @property boolean $hardwareOnSkids
 * @property integer $numSkids
 * @property string $bmCompanyName
 * @property string $bmContactName
 * @property string $bmPhoneNumber
 * @property string $bmAddress1
 * @property string $bmAddress2
 * @property string $bmCity
 * @property string $bmState
 * @property string $bmZip
 * @property string $bmCountry
 * @property string $bmCellNumber
 * @property string $bmEmailAddress
 * @property string $specialInstructions
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \App\Data\Models\Site $site
 * @property-read \App\Data\Models\File $file
 */
class PickupRequest extends Model
{
	use Eloquence, Mappable, Sortable;

    protected $table = 'pickup_request';

    protected $dates = [
        'preferred_pickup_date',
        'created_at',
        'updated_at'
    ];

    protected $maps = [
        // 'id' => 'id'
        'siteId' => 'site_id',
        'companyName' => 'company_name',
        'companyDivision' => 'company_division',
        'contactName' => 'contact_name',
        'contactPhoneNumber' => 'contact_phone_number',
        'contactAddress1' => 'contact_address_1',
        'contactAddress2' => 'contact_address_2',
        'contactCity' => 'contact_city',
        'contactState' => 'contact_state',
        'contactZip' => 'contact_zip',
        'contactCountry' => 'contact_country',
        'contactCellNumber' => 'contact_cell_number',
        'contactEmailAddress' => 'contact_email_address',
        'referenceNumber' => 'reference_number',
        'numInternalHardDrives' => 'num_internal_hard_drives',
        'numDesktops' => 'num_desktops',
        'numLaptops' => 'num_laptops',
        'numMonitors' => 'num_monitors',
        'numCRTMonitors' => 'num_crt_monitors',
        'numLCDMonitors' => 'num_lcd_monitors',
        'numPrinters' => 'num_printers',
        'numServers' => 'num_servers',
        'numNetworking' => 'num_networking',
        'numStorageSystems' => 'num_storage_systems',
        'numUps' => 'num_ups',
        'numRacks' => 'num_racks',
        'numMobilePhones' => 'num_mobile_phones',
        'numOther' => 'num_other',
        'numMisc' => 'num_misc',
        'totalNumAssets' => 'total_num_assets',
        'internalHardDriveEncrypted' => 'internal_hard_drive_encrypted',
        'internalHardDriveWiped' => 'internal_hard_drive_wiped',
        'desktopEncrypted' => 'desktop_encrypted',
        'desktopHardDriveWiped' => 'desktop_hard_drive_wiped',
        'laptopEncrypted' => 'laptop_encrypted',
        'laptopHardDriveWiped' => 'laptop_hard_drive_wiped',
        'serverEncrypted' => 'server_encrypted',
        'serverHardDriveWiped' => 'server_hard_drive_wiped',
        'preferredPickupDate' => 'preferred_pickup_date',
        'preferredPickupDateInformation' => 'preferred_pickup_date_information',
        'unitsLocatedNearDock' => 'units_located_near_dock',
        'unitsOnSingleFloor' => 'units_on_single_floor',
        'isLiftGateNeeded' => 'is_lift_gate_needed',
        'isLoadingDockPresent' => 'is_loading_dock_present',
        'dockAppointmentRequired' => 'dock_appointment_required',
        'assetsNeedPackaging' => 'assets_need_packaging',
        'hardwareOnSkids' => 'hardware_on_skids',
        'numSkids' => 'num_skids',
        'bmCompanyName' => 'bm_company_name',
        'bmContactName' => 'bm_contact_name',
        'bmPhoneNumber' => 'bm_phone_number',
        'bmAddress1' => 'bm_address_1',
        'bmAddress2' => 'bm_address_2',
        'bmCity' => 'bm_city',
        'bmState' => 'bm_state',
        'bmZip' => 'bm_zip',
        'bmCountry' => 'bm_country',
        'bmCellNumber' => 'bm_cell_number',
        'bmEmailAddress' => 'bm_email_address',
        'specialInstructions' => 'special_instructions',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function site() {
        return $this->belongsTo('App\Data\Models\Site', 'site_id', 'id');
    }

    public function file() {
        return $this->hasOne('App\Data\Models\File', 'pickup_request_id', 'id');
    }

}
