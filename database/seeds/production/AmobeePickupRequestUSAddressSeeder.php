<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AmobeePickupRequestUSAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $site = Site::where('code', '=', 'amobee')->first();

        $featureHasPickupRequestAddressBook = Feature::where('name', '=', Feature::PICKUP_REQUEST_ADDRESS_BOOK)->first();

        $site->features()->attach([$featureHasPickupRequestAddressBook->id]);

        $pickupRequestAddressBookConfiguration = array (
            'site_address_book_label'     => 'Site Address Book',
            'new_site_text'               => 'Provide to a new Site Name to create a new record.',
            'new_site_address_book_label' => 'Site Name',
            'change_text'                 => 'If you want to add this site to the address book, you must supply a new <b>Site Name</b>. Otherwise, the existing <b>Site Name</b> address record will be updated with the information submitted for this request.',
            'allow_change'                => true,
            'address_readonly'            => false,
            'save_dock_info'              => false,
        );

        $site->features()->updateExistingPivot($featureHasPickupRequestAddressBook->id, ['data' => serialize($pickupRequestAddressBookConfiguration)]);

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $site->id,
                'name' => 'Amobee - Redwood City',
                'company_name' => 'Amobee',
                'company_division' => null,
                'contact_name' => Crypt::encrypt(''),
                'contact_phone_number' => Crypt::encrypt(''),
                'contact_address_1' => Crypt::encrypt('901 Marshall St'),
                'contact_address_2' => Crypt::encrypt('STE 200'),
                'contact_city' => 'Redwood City',
                'contact_state' => 'CA',
                'contact_zip' => '94063',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt(''),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}