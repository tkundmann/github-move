<?php

namespace Database\Seeds;

use Database\Seeds\Development\DatabaseSeederDevelopment;
use Database\Seeds\Production\DatabaseSeederProduction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local'))
        {
            $this->call(DatabaseSeederDevelopment::class);
        }
        else if (App::environment('production'))
        {
            $this->call(DatabaseSeederProduction::class);
        }
    }
}
