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

}
