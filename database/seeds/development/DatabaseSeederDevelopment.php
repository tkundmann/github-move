<?php

namespace Database\Seeds\Development;

use Illuminate\Database\Seeder;

class DatabaseSeederDevelopment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SiteTableSeeder::class);
        $this->call(FeatureTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(VendorClientTableSeeder::class);
        $this->call(LotNumberTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(ShipmentTableSeeder::class);
        $this->call(AssetTableSeeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(FileTableSeeder::class);
        $this->call(PickupRequestAddressSeeder::class);
    }
}
