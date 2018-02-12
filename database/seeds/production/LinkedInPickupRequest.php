<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LinkedInPickupRequest extends Seeder
{
		/**
     * Run the database seeds.
     *
     * @return void
     */
		public function run() {

		$featureHasPickupRequest = Feature::where('name', '=', Feature::HAS_PICKUP_REQUEST)->first();

		$site = Site::where('code', '=', 'linkedin')->first();

		$site->features()->attach([$featureHasPickupRequest->id]);

		$pickupRequestConfiguration = array (
			'password' => Hash::make('lnk02092018sar!'),
			'title' => 'Sipi Asset Recovery/LinkedIn Pickup Request',
			'use_company_division' => false,
			'use_contact_section_title' => false,
			'use_state_as_select' => false,
			'use_country' => true,
			'use_reference_number' => true,
			'reference_number_label' => 'Customer Reference Number',
			'use_alternative_piece_count_form' => false,
			'use_crt_and_lcd_monitors' => true,
			'use_preferred_pickup_date' => true,
			'use_preferred_pickup_date_information' => false,
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
				10 => 'preferred_pickup_date',
				11 => 'units_located_near_dock',
				12 => 'units_on_single_floor',
				13 => 'is_loading_dock_present',
				14 => 'dock_appointment_required',
				15 => 'assets_need_packaging',
			),
			'email_from' => 'SARLinkedIn@sipiar.com',
			'email_bcc' => 'SARLinkedIn@sipiar.com;tony@lynch2.com',
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
					'name' => 'US Equipment List',
					'filename' => 'LinkedIn_EquipmentList_US.xls',
					'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/linkedin/pickup_request/equipment_list_templates/LinkedIn_EquipmentList_US.xls',
				),
			1 =>
				array (
					'name' => 'EMEA Equipment List',
					'filename' => 'LinkedIn_EquipmentList_EMEA.xls',
					'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/linkedin/pickup_request/equipment_list_templates/LinkedIn_EquipmentList_EMEA.xls',
				),
			);

		$site->features()->updateExistingPivot($featureHasPickupRequestEquipList->id, ['data' => serialize($pickupRequestEquipListConfiguration)]);
	}
}
