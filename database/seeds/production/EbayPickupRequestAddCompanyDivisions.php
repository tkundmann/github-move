<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EbayPickupRequestAddCompanyDivisions extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run() {

		$site = Site::where('code', '=', 'ebay')->first();

		$featureHasPickupRequest = Feature::where('name', '=', Feature::HAS_PICKUP_REQUEST)->first();

		$pickupRequestConfiguration = array (
			'password' => Hash::make('ebay04052014btr'),
			'title' => 'Sipi Asset Recovery/Ebay Pickup Request',
			'use_company_division' => true,
			'company_division_label' => 'Division',
			'company_divisions' => array (
				'ITSS (EBAYIT)' => 'ITSS (EBAYIT)',
				'SITE (EBAYOPS)' => 'SITE (EBAYOPS)',
			),
			'use_contact_section_title' => true,
			'contact_section_title' => 'Site Location Pick-up Information',
			'use_state_as_select' => false,
			'use_country' => false,
			'use_reference_number' => true,
			'reference_number_label' => 'RITM Number',
			'use_alternative_piece_count_form' => false,
			'use_preferred_pickup_date' => true,
			'use_preferred_pickup_date_information' => false,
			'use_lift_gate' => true,
			'use_hardware_on_skids' => true,
			'required_fields' =>
			array (
				'contact_name',
				'contact_address_1',
				'contact_city',
				'contact_state',
				'contact_zip',
				'company_division',
				'contact_phone_number',
				'contact_email_address',
				'reference_number',
				'preferred_pickup_date',
				'hardware_on_skids',
				'units_located_near_dock',
				'units_on_single_floor',
				'is_lift_gate_needed',
				'is_loading_dock_present',
				'dock_appointment_required',
			),
			'email_from' => 'btrebay@sipiar.com',
			'email_bcc' => 'btrebay@sipiar.com;DL-eBay-Ops-ALM-Global-Disposal@ebay.com;vramirezbonilla@ebay.com;kevmurphy@ebay.com;tony@lynch2.com',
			'email_additional_bcc' =>
			array (
			),
		);

		$site->features()->updateExistingPivot($featureHasPickupRequest->id, ['data' => serialize($pickupRequestConfiguration)]);

	}
}
