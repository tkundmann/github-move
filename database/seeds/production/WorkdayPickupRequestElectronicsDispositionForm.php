<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WorkdayPickupRequestElectronicsDispositionForm extends Seeder
{
		/**
     * Run the database seeds.
     *
     * @return void
     */
		public function run() {

		$featureHasPickupRequestElectronicsDispositionForm = Feature::where('name', '=', Feature::PICKUP_REQUEST_SAR_BOX_PROGRAM)->first();

		$site->features()->attach([$featureHasPickupRequestElectronicsDispositionForm->id]);

		$pickupRequestElectronicsDispositionFormConfiguration = array (
			0 =>
				array (
					'name' => 'Electronics Disposition Form',
					'filename' => 'Workday_ElectronicsDispositionForm.xlsx',
					'url' => 'https://belmont-sipi-assets.s3.amazonaws.com/workday/pickup_request/electronics_disposition_forms/Workday_ElectronicsDispositionForm.xlsx',
				),
			);

		$site->features()->updateExistingPivot($featureHasPickupRequestElectronicsDispositionForm->id, ['data' => serialize($pickupRequestElectronicsDispositionFormConfiguration)]);

	}
}
