<?php

namespace Database\Seeds\Production;

use Illuminate\Database\Seeder;
use App\Data\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            [
                'name' => Role::USER,
                'display_name' => 'User',
                'description' => 'Can search Shipments and Assets that belongs to the associated site.',
                'created_at' => Carbon::now()
            ],
            [
                'name' => Role::SUPERUSER,
                'display_name' => 'Super User',
                'description' => 'Can search all Shipments and Assets.',
                'created_at' => Carbon::now()
            ],
            [
                'name' => Role::ADMIN,
                'display_name' => 'Admin',
                'description' => 'Can manage users and superusers, delete Shipments and Assets, manage uploads.',
                'created_at' => Carbon::now()
            ],
            [
                'name' => Role::SUPERADMIN,
                'display_name' => 'Super Admin',
                'description' => 'Can perform standard Admin functions and manage Admin accounts.',
                'created_at' => Carbon::now()
            ]
        ]);

    }
}
