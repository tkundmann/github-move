<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordExpiryDaysToSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site', function (Blueprint $table) {
            $table->integer('password_expiry_days')->after('type')->nullable()->default(90);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site', function (Blueprint $table) {
            $table->dropColumn('password_expiry_days');
        });
    }
}
