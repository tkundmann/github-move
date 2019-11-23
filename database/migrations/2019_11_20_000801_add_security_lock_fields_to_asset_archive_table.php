<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecurityLockFieldsToAssetArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_archive', function (Blueprint $table) {
            $table->boolean('security_lock')->after('status')->nullable()->default(null);
            $table->boolean('security_lock_resolved')->after('security_lock')->nullable()->default(null);
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
            $table->dropColumn('security_lock');
            $table->dropColumn('security_lock_resolved');
        });
    }
}
