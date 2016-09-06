<?php

namespace Database\Seeds\Development;

use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
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
    
        DB::table('user')->insert([
            [
                'name' => 'user',
                'email' => 'user@example.com',
                'password' => bcrypt('user'),
                'site_id' => $site1->id,
                'disabled' => false,
                'confirmed' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'user2',
                'email' => 'user2@example.com',
                'password' => bcrypt('user2'),
                'site_id' => $site2->id,
                'disabled' => false,
                'confirmed' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'disabled user',
                'email' => 'user_disabled@example.com',
                'password' => bcrypt('user_disabled'),
                'site_id' => $site1->id,
                'disabled' => true,
                'confirmed' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'not confirmed user',
                'email' => 'user_notconfirmed@example.com',
                'password' => bcrypt('user_notconfirmed'),
                'site_id' => $site1->id,
                'disabled' => false,
                'confirmed' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'superuser',
                'email' => 'superuser@example.com',
                'password' => bcrypt('superuser'),
                'site_id' => null,
                'disabled' => false,
                'confirmed' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
                'site_id' => null,
                'disabled' => false,
                'confirmed' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'superadmin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('superadmin'),
                'site_id' => null,
                'disabled' => false,
                'confirmed' => true,
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
