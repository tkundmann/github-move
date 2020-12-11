<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProvidenceStJosephPickupRequestAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $site = Site::where('code', '=', 'providencestjoseph')->first();

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
                'name' => 'Providence St. Joseph Health - Portland - Betsy Andrews',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Betsy Andrews'),
                'contact_phone_number' => Crypt::encrypt('(503) 586-7309'),
                'contact_address_1' => Crypt::encrypt('2320 Lloyd Center'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Portland',
                'contact_state' => 'OR',
                'contact_zip' => '97232',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('betsy.andrews@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Portland - Sherie Drew',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Sherie Drew'),
                'contact_phone_number' => Crypt::encrypt('(503) 862-6423'),
                'contact_address_1' => Crypt::encrypt('2320 Lloyd Center'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Portland',
                'contact_state' => 'OR',
                'contact_zip' => '97232',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('sherie.drew@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Portland - Kathy Finley',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Kathy Finley'),
                'contact_phone_number' => Crypt::encrypt('(971) 358-0588'),
                'contact_address_1' => Crypt::encrypt('2320 Lloyd Center'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Portland',
                'contact_state' => 'OR',
                'contact_zip' => '97232',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('kathleen.finley@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Portland - Sridhar Pinnamaneni',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Sridhar Pinnamaneni'),
                'contact_phone_number' => Crypt::encrypt('(971) 282-0760'),
                'contact_address_1' => Crypt::encrypt('2320 Lloyd Center'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Portland',
                'contact_state' => 'OR',
                'contact_zip' => '97232',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('sridhar.pinnamaneni@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Renton - Katie Benedict',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Katie Benedict'),
                'contact_phone_number' => Crypt::encrypt('(206) 629-8573'),
                'contact_address_1' => Crypt::encrypt('2001 Lind Ave Sw'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Renton',
                'contact_state' => 'WA',
                'contact_zip' => '98057',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('katie.benedict@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Renton - Takenori Justice',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Takenori Justice'),
                'contact_phone_number' => Crypt::encrypt('(971) 358-2371'),
                'contact_address_1' => Crypt::encrypt('2001 Lind Ave Sw'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Renton',
                'contact_state' => 'WA',
                'contact_zip' => '98057',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('takenori.justice@providence.org'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site->id,
                'name' => 'Providence St. Joseph Health - Irvine - David.Stuckert',
                'company_name' => 'Providence St. Joseph Health',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('David Stuckert'),
                'contact_phone_number' => Crypt::encrypt(''),
                'contact_address_1' => Crypt::encrypt('2001 Lind Ave Sw'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Irvine',
                'contact_state' => 'CA',
                'contact_zip' => '92612',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('david.stuckert@providence.org'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}
