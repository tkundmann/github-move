<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBiosSecurityLockFieldsToStringAssetArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_archive', function (Blueprint $table) {
            $table->string('security_lock', 5)->nullable()->default(null)->change();
            $table->string('security_lock_resolved', 5)->nullable()->default(null)->change();
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
            $table->boolean('security_lock')->nullable()->default(null)->change();
            $table->boolean('security_lock_resolved')->nullable()->default(null)->change();
        });
    }
}
