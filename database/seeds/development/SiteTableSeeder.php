<?php

namespace Database\Seeds\Development;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site')->insert([
            [
                'code' => 'site1',
                'title' => 'Site #1',
                'logo_url' => '/img/logo/logo-insight.png',
                'color' => '#CB0600',
                'type' => 'Insight',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'code' => 'site2',
                'title' => 'Site #2',
                'logo_url' => '/img/logo/logo-sipi.png',
                'color' => '#601986',
                'type' => 'Insight',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
