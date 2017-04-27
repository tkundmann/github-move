<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PayPalPickupRequestAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paypal = Site::where('code', '=', 'paypal')->first();

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $paypal->id,
                'name' => 'PayPal',
                'company_name' => 'PayPal',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Jeannie Quang'),
                'contact_phone_number' => Crypt::encrypt(''),
                'contact_address_1' => Crypt::encrypt(''),
                'contact_address_2' => null,
                'contact_city' => null,
                'contact_state' => null,
                'contact_zip' => null,
                'contact_country' => null,
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('jquang@paypal.com'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}
