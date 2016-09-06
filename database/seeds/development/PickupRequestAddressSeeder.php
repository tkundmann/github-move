<?php

namespace Database\Seeds\Development;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PickupRequestAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site2 = Site::where('code', '=', 'site2')->first();
    
        DB::table('pickup_request_address')->insert([
            [
                'site_id' => $site2->id,
                'name' => 'Ashburn, 44490 Chilum Place',
                'company_name' => 'Yahoo',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('John Doe'),
                'contact_phone_number' => Crypt::encrypt('543543211'),
                'contact_address_1' => Crypt::encrypt('Nowhere Street 1c'),
                'contact_address_2' => null,
                'contact_city' => 'Dublin',
                'contact_state' => 'N/A',
                'contact_zip' => '48075',
                'contact_country' => 'Ireland',
                'contact_cell_number' => Crypt::encrypt('505606707'),
                'contact_email_address' => Crypt::encrypt('JohnDoe@example.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site2->id,
                'name' => 'Southfield, 4000 Town Center, Suite 400,',
                'company_name' => 'Yahoo',
                'company_division' => 'IT',
                'contact_name' => Crypt::encrypt('Mary Smith'),
                'contact_phone_number' => Crypt::encrypt('34243221'),
                'contact_address_1' => Crypt::encrypt('Sunny Farm 12'),
                'contact_address_2' => null,
                'contact_city' => 'Southfield',
                'contact_state' => 'MI',
                'contact_zip' => '48076',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt('101202303'),
                'contact_email_address' => Crypt::encrypt('mary@example.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $site2->id,
                'name' => 'Santa Monica, 2400 Broadway',
                'company_name' => 'Yahoo',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Stephen Williams'),
                'contact_phone_number' => Crypt::encrypt('543543215'),
                'contact_address_1' => Crypt::encrypt('Main Lane 2'),
                'contact_address_2' => null,
                'contact_city' => 'Santa Monica',
                'contact_state' => 'CA',
                'contact_zip' => '90404',
                'contact_country' => 'USA',
                'contact_cell_number' => Crypt::encrypt('999888777'),
                'contact_email_address' => Crypt::encrypt('steve@example.com'),
                'created_at' => Carbon::now()
            ]
        ]);
    
    }
}