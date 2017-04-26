<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCRTAndLCDMonitorsToPickupRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickup_request', function (Blueprint $table) {
            $table->integer('num_crt_monitors')->nullable()->default(null);
            $table->integer('num_lcd_monitors')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickup_request', function (Blueprint $table) {
            $table->dropColumn('num_crt_monitors');
            $table->dropColumn('num_lcd_monitors');
        });
    }
}
