<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginToAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->string('origin', 10)->before('status')->nullable()->default(null);
        });

        Schema::table('asset_archive', function (Blueprint $table) {
            $table->string('origin', 10)->before('status')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->dropColumn('origin');
        });

        Schema::table('asset_archive', function (Blueprint $table) {
            $table->dropColumn('origin');
        });
    }
}
