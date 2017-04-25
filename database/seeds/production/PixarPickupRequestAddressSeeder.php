<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PixarPickupRequestAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pixar = Site::where('code', '=', 'pixar')->first();

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $pixar->id,
                'name' => 'Pixar Animation Studios',
                'company_name' => 'Pixar Animation Studios',
                'company_division' => null,
                'contact_name' => 'Nick Zehner',
                'contact_phone_number' => '510-922-4014',
                'contact_address_1' => Crypt::encrypt(''),
                'contact_address_2' => null,
                'contact_city' => null,
                'contact_state' => null,
                'contact_zip' => null,
                'contact_country' => null,
                'contact_cell_number' => '510-366-6306',
                'contact_email_address' => 'banana@pixar.com',
                'created_at' => Carbon::now()
            ]
        ]);

    }
}