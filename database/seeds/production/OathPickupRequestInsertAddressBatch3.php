<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OathPickupRequestInsertAddressBatch3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oath = Site::where('code', '=', 'oath')->first();

        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $oath->id,
                'name' => 'AEZ/AEB',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Etisalat SmartHub Data Center Old Exchange Building'),
                'contact_address_2' => Crypt::encrypt('Al Salaam Street'),
                'contact_city' => 'Fujaira, P.O. Box 14',
                'contact_state' => '',
                'contact_zip' => '',
                'contact_country' => 'United Arab Emirates',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}