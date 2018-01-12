<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasDockToPickupRequestAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickup_request_address', function (Blueprint $table) {
            $table->boolean('has_dock')->after('contact_email_address')->nullable()->default(false);
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
            $table->dropColumn('has_dock');
        });
    }
}
