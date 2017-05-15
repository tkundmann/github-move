<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MagentoPickupRequestAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $magento = Site::where('code', '=', 'magento')->first();

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $magento->id,
                'name' => 'Magento',
                'company_name' => 'Magento',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Henry Bruemer'),
                'contact_phone_number' => Crypt::encrypt('408-680-8454'),
                'contact_address_1' => Crypt::encrypt('54 N. Central Ave'),
                'contact_address_2' => null,
                'contact_city' => 'Campbell',
                'contact_state' => 'CA',
                'contact_zip' => '95008',
                'contact_country' => null,
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('hbruemer@magento.com'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}