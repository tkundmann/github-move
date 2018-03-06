<?php

namespace Database\Seeds\Production;

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
        $amobee = Site::where('code', '=', 'amobee')->first();

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $amobee->id,
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