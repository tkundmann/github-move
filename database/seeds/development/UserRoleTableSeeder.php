<?php

namespace Database\Seeds\Development;

use Illuminate\Database\Seeder;

use App\Data\Models\User;
use App\Data\Models\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleUser = Role::where('name', '=', Role::USER)->first();
        $roleSuperUser = Role::where('name', '=', Role::SUPERUSER)->first();
        $roleAdmin = Role::where('name', '=', Role::ADMIN)->first();
        $roleSuperAdmin = Role::where('name', '=', Role::SUPERADMIN)->first();

        $user = User::where('email', '=', 'user@example.com')->first();
        $user->attachRole($roleUser);

        $user = User::where('email', '=', 'user2@example.com')->first();
        $user->attachRole($roleUser);

        $user = User::where('email', '=', 'user_disabled@example.com')->first();
        $user->attachRole($roleUser);

        $user = User::where('email', '=', 'user_notconfirmed@example.com')->first();
        $user->attachRole($roleUser);

        $user = User::where('email', '=', 'superuser@example.com')->first();
        $user->attachRole($roleSuperUser);

        $user = User::where('email', '=', 'admin@example.com')->first();
        $user->attachRole($roleAdmin);

        $user = User::where('email', '=', 'superadmin@example.com')->first();
        $user->attachRole($roleSuperAdmin);
    }
}
