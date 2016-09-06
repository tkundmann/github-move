<?php

use Illuminate\Database\Migrations\Migration;

class AddUserSiteRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function ($table) {
            $table->integer('site_id')->unsigned()->nullable()->after('remember_token');

            $table->foreign('site_id')->references('id')->on('site')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        Schema::table('user', function ($table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        DB::statement('SET foreign_key_checks = 1;');
    }
}
