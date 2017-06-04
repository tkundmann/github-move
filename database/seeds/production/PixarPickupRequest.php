<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PixarPickupRequest extends Seeder
{
		/**
     * Run the database seeds.
     *
     * @return void
     */
		public function run() {

		$featureHasPickupRequest = Feature::where('name', '=', Feature::HAS_PICKUP_REQUEST)->first();

		$site = Site::where('code', '=', 'pixar')->first();

		$site->features()->attach([$featureHasPickupRequest->id]);

		$pickupRequestConfiguration = array (
			'password' => Hash::make('px04242017sar!'),
			'title' => 'Sipi Asset Recovery/Pixar Pickup Request',
			'use_company_division' => false,
			'use_contact_section_title' => false,
			'use_state_as_select' => true,
			'states' =>
			array (
				'Alabama' => 'AL',
				'Alaska' => 'AK',
				'Arizona' => 'AZ',
				'Arkansas' => 'AR',
				'California' => 'CA',
				'Colorado' => 'CO',
				'Conneticut' => 'CT',
				'Delaware' => 'DE',
				'Florida' => 'FL',
				'Georgia' => 'GA',
				'Hawaii' => 'HI',
				'Idaho' => 'ID',
				'Illinois' => 'IL',
				'Indiana' => 'IN',
				'Iowa' => 'IA',
				'Kansas' => 'KS',
				'Kentucky' => 'KY',
				'Louisiana' => 'LA',
				'Maryland' => 'MD',
				'Maine' => 'ME',
				'Massachusetts' => 'MA',
				'Michigan' => 'MI',
				'Minnesota' => 'MN',
				'Mississippi' => 'MS',
				'Missouri' => 'MO',
				'Montana' => 'MT',
				'Nebraska' => 'NE',
				'Nevada' => 'NV',
				'New Hampshire' => 'NH',
				'New Jersey' => 'NJ',
				'New Mexico' => 'NM',
				'New York' => 'NY',
				'North Carolina' => 'NC',
				'North Dakota' => 'ND',
				'Ohio' => 'OH',
				'Oklahoma' => 'OK',
				'Oregon' => 'OR',
				'Pennsylvania' => 'PA',
				'Rhode Island' => 'RI',
				'South Carolina' => 'SC',
				'South Dakota' => 'SD',
				'Tennessee' => 'TN',
				'Texas' => 'TX',
				'Utah' => 'UT',
				'Vermont' => 'VT',
				'Virginia' => 'VA',
				'Washington' => 'WA',
				'Washington D.C.' => 'DC',
				'West Virginia' => 'WV',
				'Wisconsin' => 'WI',
				'Wyoming' => 'WY',
			),
			'use_country' => false,
			'use_reference_number' => true,
			'reference_number_label' => 'Customer Reference Number',
			'use_alternative_piece_count_form' => false,
			'use_preferred_pickup_date' => false,
			'use_preferred_pickup_date_information' => true,
			'use_lift_gate' => false,
			'use_hardware_on_skids' => true,
			'required_fields' =>
			array (
				0 => 'upload_equipment_list',
				1 => 'company_name',
				2 => 'contact_name',
				3 => 'contact_address_1',
				4 => 'contact_city',
				5 => 'contact_state',
				6 => 'contact_zip',
				7 => 'contact_phone_number',
				8 => 'contact_email_address',
				9 => 'reference_number',
				10 => 'preferred_pickup_date_information',
				11 => 'units_located_near_dock',
				12 => 'units_on_single_floor',
				13 => 'is_loading_dock_present',
				14 => 'dock_appointment_required',
				15 => 'assets_need_packaging',
			),
			'email_from' => 'SARPixar@sipiar.com',
			'email_bcc' => 'SARPixar@sipiar.com;tony@lynch2.com',
			'email_additional_bcc' =>
			array (
			),
		);

		$site->features()->updateExistingPivot($featureHasPickupRequest->id, ['data' => serialize($pickupRequestConfiguration)]);

		$featureHasPickupRequestEquipList = Feature::where('name', '=', Feature::PICKUP_REQUEST_EQUIPMENT_LIST)->first();

		$site->features()->attach([$featureHasPickupRequestEquipList->id]);

		$pickupRequestEquipListConfiguration = array (
			0 =>
				array (
					'name' => 'Equipment List',
					'filename' => 'Pixar_EquipmentList.xls',
					'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/pixar/pickup_request/equipment_list_templates/Pixar_EquipmentList.xls?v=4',
				),
			);

		$site->features()->updateExistingPivot($featureHasPickupRequestEquipList->id, ['data' => serialize($pickupRequestEquipListConfiguration)]);
	}
}
