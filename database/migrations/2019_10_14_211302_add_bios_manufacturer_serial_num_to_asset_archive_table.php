<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiosManufacturerSerialNumToAssetArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_archive', function (Blueprint $table) {
            $table->string('bios_manufacturer_serial_num', 50)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_archive', function (Blueprint $table) {
            $table->dropColumn('bios_manufacturer_serial_num');
        });
    }
}
