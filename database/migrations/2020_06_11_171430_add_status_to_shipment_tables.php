<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToShipmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment', function (Blueprint $table) {
            $table->string('status', 40)->after('lot_number')->nullable()->default(null);
        });

        Schema::table('shipment_archive', function (Blueprint $table) {
            $table->string('status', 40)->after('lot_number')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('shipment_archive', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
