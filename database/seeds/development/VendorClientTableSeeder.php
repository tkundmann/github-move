<?php

namespace Database\Seeds\Development;

use App\Data\Models\Site;
use App\Data\Models\User;
use App\Data\Models\VendorClient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendor_client')->insert([
            [
                'name' => 'YAHOO IT',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'YAHOO OPS',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'EOT',
                'created_at' => Carbon::now()
            ]
        ]);

        $vendorClient1 = VendorClient::where('name', '=', 'YAHOO IT')->first();
        $vendorClient2 = VendorClient::where('name', '=', 'YAHOO OPS')->first();
        $vendorClient3 = VendorClient::where('name', '=', 'EOT')->first();

        $site1 = Site::where('code', '=', 'site1')->first();
        $site2 = Site::where('code', '=', 'site2')->first();

        $site1->vendorClients()->attach([$vendorClient1->id, $vendorClient3->id]);
        $site2->vendorClients()->attach([$vendorClient1->id, $vendorClient2->id]);

        $user1 = User::where('email', '=', 'user@example.com')->first();

        $user1->vendorClients()->attach([$vendorClient1->id]);
    }
}