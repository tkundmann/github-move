<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EbayPickupRequestEquipmentList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

			$site = Site::where('code', '=', 'ebay')->first();

			$featureHasPickupRequestEquipList = Feature::where('name', '=', Feature::PICKUP_REQUEST_EQUIPMENT_LIST)->first();

			$site->features()->attach([$featureHasPickupRequestEquipList->id]);

			$pickupRequestEquipListConfiguration = array (
				0 =>
					array (
						'name' => 'Equipment List',
						'filename' => 'Ebay_EquipmentList.xlsx',
						'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/ebay/pickup_request/equipment_list_templates/Ebay_EquipmentList.xlsx',
					),
			);

		$site->features()->updateExistingPivot($featureHasPickupRequestEquipList->id, ['data' => serialize($pickupRequestEquipListConfiguration)]);
	}
}
