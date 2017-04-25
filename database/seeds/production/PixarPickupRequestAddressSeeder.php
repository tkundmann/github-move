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
                'contact_name' => Crypt::encrypt('Nick Zehner'),
                'contact_phone_number' => Crypt::encrypt('510-922-4014'),
                'contact_address_1' => Crypt::encrypt('1200 Park Ave'),
                'contact_address_2' => null,
                'contact_city' => 'Emeryville',
                'contact_state' => 'CA',
                'contact_zip' => '94608',
                'contact_country' => null,
                'contact_cell_number' => Crypt::encrypt('510-366-6306'),
                'contact_email_address' => Crypt::encrypt('banana@pixar.com'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}