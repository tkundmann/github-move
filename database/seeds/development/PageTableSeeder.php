<?php

namespace Database\Seeds\Development;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site1 = Site::where('code', '=', 'site1')->first();
        $site2 = Site::where('code', '=', 'site2')->first();

        DB::table('page')->insert([
            [
                'name' => 'Page #1',
                'code' => 'page_1',
                'text' => 'Welcome to Page #1',
                'description' => 'This is Page #1',
                'site_id' => $site1->id,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Page #2',
                'code' => 'page_2',
                'text' => 'Welcome to Page #2',
                'description' => 'This is Page #2',
                'site_id' => $site1->id,
                'created_at' => Carbon::now()
            ]
        ]);

    }
}