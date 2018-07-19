<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnaplanPickupRequest extends Seeder
{
		/**
     * Run the database seeds.
     *
     * @return void
     */
		public function run() {

		$featureHasPickupRequest = Feature::where('name', '=', Feature::HAS_PICKUP_REQUEST)->first();

		$site = Site::where('code', '=', 'anaplan')->first();

		$site->features()->attach([$featureHasPickupRequest->id]);

		$pickupRequestConfiguration = array (
			'password' => Hash::make('anpl07192018sar@'),
			'title' => 'Sipi Asset Recovery/Anaplan Pickup Request',
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
				'upload_equipment_list',
				'company_name',
				'contact_name',
				'contact_address_1',
				'contact_city',
				'contact_phone_number',
				'contact_email_address',
				'reference_number',
				'preferred_pickup_date',
				'units_located_near_dock',
				'units_on_single_floor',
				'is_loading_dock_present',
				'dock_appointment_required',
				'assets_need_packaging'
			),
			'email_from' => 'SARAnaplan@sipiar.com',
			'email_bcc' => 'SARAnaplan@sipiar.com;tony@lynch2.com',
			'email_additional_bcc' => array (),
		);

		$site->features()->updateExistingPivot($featureHasPickupRequest->id, ['data' => serialize($pickupRequestConfiguration)]);

		$featureHasPickupRequestEquipList = Feature::where('name', '=', Feature::PICKUP_REQUEST_EQUIPMENT_LIST)->first();

		$site->features()->attach([$featureHasPickupRequestEquipList->id]);

		$pickupRequestEquipListConfiguration = array (
			0 =>
				array (
					'name' => 'Equipment List',
					'filename' => 'Anaplan_EquipmentList.xls',
					'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/anaplan/pickup_request/equipment_list_templates/Anaplan_EquipmentList.xls',
				),
			);

		$site->features()->updateExistingPivot($featureHasPickupRequestEquipList->id, ['data' => serialize($pickupRequestEquipListConfiguration)]);


	}
}
