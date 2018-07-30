<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMobilePhonesToPickuprequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickuprequest', function (Blueprint $table) {
            $table->integer('num_mobile_phones')>after('num_racks')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickuprequest', function (Blueprint $table) {
            $table->dropColumn('num_mobile_phones');
        });
    }
}
