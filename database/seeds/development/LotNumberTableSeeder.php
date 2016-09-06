<?php

namespace Database\Seeds\Development;

use App\Data\Models\Site;
use App\Data\Models\User;
use App\Data\Models\LotNumber;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotNumberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lot_number')->insert([
            [
                'prefix' => 'BTWYOP',
                'created_at' => Carbon::now()
            ],
            [
                'prefix' => 'BTR',
                'created_at' => Carbon::now()
            ],
            [
                'prefix' => 'MXHP',
                'created_at' => Carbon::now()
            ]
        ]);

        $lotNumber1 = LotNumber::where('prefix', '=', 'BTWYOP')->first();
        $lotNumber2 = LotNumber::where('prefix', '=', 'BTR')->first();
        $lotNumber3 = LotNumber::where('prefix', '=', 'MXHP')->first();

        $site1 = Site::where('code', '=', 'site1')->first();
        $site2 = Site::where('code', '=', 'site2')->first();

        $site1->lotNumbers()->attach([$lotNumber1->id, $lotNumber2->id, $lotNumber3->id]);

        $user1 = User::where('email', '=', 'user@example.com')->first();

        $user1->lotNumbers()->attach([$lotNumber1->id, $lotNumber2->id]);
    }
}