<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalDockInfoToPickupRequestAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickup_request_address', function (Blueprint $table) {
            $table->boolean('dock_appointment_required')->after('has_dock')->nullable()->default(false);
            $table->boolean('units_located_near_dock')->after('dock_appointment_required')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickup_request_address', function (Blueprint $table) {
            $table->dropColumn('dock_appointment_required');
            $table->dropColumn('units_located_near_dock');
        });
    }
}
