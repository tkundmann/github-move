<?php

namespace Database\Seeds\Production;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OathPickupRequestInsertAddressBatch2 extends Seeder
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
                'name' => 'SEZ/SEB',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('00353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Equinix (Sweden) AB SK1 Mariehallsvagen 36'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Bromma, Stockholm',
                'contact_state' => '',
                'contact_zip' => '',
                'contact_country' => 'Sweden',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'WAZ/WAA',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('00353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Al. Jerozolimskie 65/79'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Warsaw',
                'contact_state' => 'Masovian',
                'contact_zip' => '00-697',
                'contact_country' => 'Poland',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'ESZ/ESA',
                'company_name' => 'Interxion',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Calle Albasanz 71'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Madrid',
                'contact_state' => '',
                'contact_zip' => '28037',
                'contact_country' => 'Spain',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'BGZ/BGA',
                'company_name' => 'Telepoint',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('122 Ovche Pole Str.'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Sofia',
                'contact_state' => '',
                'contact_zip' => '1303',
                'contact_country' => 'Bulgaria',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'ITZ/ITA',
                'company_name' => 'Cdlan',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Flat Building E (2 Storey)'),
                'contact_address_2' => Crypt::encrypt('Via Caldera, 21'),
                'contact_city' => 'Milan',
                'contact_state' => '',
                'contact_zip' => '20153',
                'contact_country' => 'Italy',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'FRY/FRB',
                'company_name' => 'Interxion',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('40 Avenue Roger Salengro'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Marseille',
                'contact_state' => '',
                'contact_zip' => '',
                'contact_country' => 'France',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'VIZ/VIA',
                'company_name' => 'Interxion',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Louis-Haefliger-Gasse 10'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Vienna',
                'contact_state' => '',
                'contact_zip' => '1210',
                'contact_country' => 'Austria',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'ROZ/ROB',
                'company_name' => 'Nxdata',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('8, Dimitrie Pompeiu'),
                'contact_address_2' => Crypt::encrypt('FEPER Building, 3rd Floor'),
                'contact_city' => 'Bucharest',
                'contact_state' => '',
                'contact_zip' => '20337',
                'contact_country' => 'Romania',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'AEZ/AEB',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('International Media Production Zone (IMPZ)'),
                'contact_address_2' => Crypt::encrypt('Building F90'),
                'contact_city' => 'Dubai',
                'contact_state' => '',
                'contact_zip' => '',
                'contact_country' => 'UAE',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'ZAZ/ZAA',
                'company_name' => 'Teraco',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Teraco Data Environments, 5 Brewery Road'),
                'contact_address_2' => Crypt::encrypt('Isando, Gauteng'),
                'contact_city' => 'Johannesburg',
                'contact_state' => '',
                'contact_zip' => '1600',
                'contact_country' => 'South Africa',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'DEZ/DEB',
                'company_name' => 'Yahoo! c/o Interxion',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Hanauer Landstrasse 302'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'Frankfurt',
                'contact_state' => '',
                'contact_zip' => '60314',
                'contact_country' => 'Germany',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'FRZ/FRA',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Equinix Telecity Paris Condorcet'),
                'contact_address_2' => Crypt::encrypt('10 rue Waldeck Rochet - Building 520'),
                'contact_city' => 'Aubervilliers',
                'contact_state' => '',
                'contact_zip' => '93300',
                'contact_country' => 'France',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'LOZ/LOB',
                'company_name' => 'Telehouse',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('22 Coriander Avenue'),
                'contact_address_2' => Crypt::encrypt(''),
                'contact_city' => 'London',
                'contact_state' => '',
                'contact_zip' => 'E14 2AA',
                'contact_country' => 'UK',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'AMC',
                'company_name' => 'Equinix',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Science Park 610'),
                'contact_address_2' => Crypt::encrypt('Cage 40101'),
                'contact_city' => 'Amsterdam',
                'contact_state' => '',
                'contact_zip' => '1098 XH NL',
                'contact_country' => 'Netherlands',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ],
            [
                'site_id' => $oath->id,
                'name' => 'AMS/AMB/AME',
                'company_name' => 'Vancis/Interxion',
                'company_division' => 'Operations',
                'contact_name' => Crypt::encrypt('Gerard Scahill'),
                'contact_phone_number' => Crypt::encrypt('353 87 9043265'),
                'contact_address_1' => Crypt::encrypt('Interxion'),
                'contact_address_2' => Crypt::encrypt('Science Park 121'),
                'contact_city' => 'Amsterdam',
                'contact_state' => '',
                'contact_zip' => '1098 XG',
                'contact_country' => 'Netherlands',
                'contact_cell_number' => Crypt::encrypt(''),
                'contact_email_address' => Crypt::encrypt('gscahill@oath.com'),
                'created_at' => Carbon::now()
            ]
        ]);

    }
}