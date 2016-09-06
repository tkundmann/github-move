<?php

namespace Database\Seeds\Production;

use Illuminate\Database\Seeder;

class DatabaseSeederProduction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FeatureTableSeeder::class);
        $this->call(RoleTableSeeder::class);
    }
}
