<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OathPickupRequestInsertAddress extends Seeder
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
                'name' => 'Verizon Digital Media Services',
                'company_name' => 'Verizon Digital Media Services',
                'company_division' => null,
                'contact_name' => Crypt::encrypt('Mandip Shrestha'),
                'contact_phone_number' => Crypt::encrypt('424-280-9477'),
                'contact_address_1' => Crypt::encrypt('13031 W Jefferson Blvd'),
                'contact_address_2' => Crypt::encrypt('Bld #900'),
                'contact_city' => 'Los Angeles',
                'contact_state' => 'CA',
                'contact_zip' => '90094',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt(''),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}