<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_client', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('site_vendor_client', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('site_id')->unsigned();
            $table->integer('vendor_client_id')->unsigned();

            $table->foreign('site_id')->references('id')->on('site')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vendor_client_id')->references('id')->on('vendor_client')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['site_id', 'vendor_client_id']);
        });

        Schema::create('user_vendor_client', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('user_id')->unsigned();
            $table->integer('vendor_client_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vendor_client_id')->references('id')->on('vendor_client')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'vendor_client_id']);
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
        Schema::drop('site_vendor_client');
        Schema::drop('user_vendor_client');
        Schema::drop('vendor_client');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
