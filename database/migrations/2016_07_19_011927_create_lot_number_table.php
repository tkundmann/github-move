<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_number', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('prefix')->unique();
            $table->timestamps();
        });

        Schema::create('site_lot_number', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('site_id')->unsigned();
            $table->integer('lot_number_id')->unsigned();

            $table->foreign('site_id')->references('id')->on('site')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lot_number_id')->references('id')->on('lot_number')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['site_id', 'lot_number_id']);
        });

        Schema::create('user_lot_number', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('user_id')->unsigned();
            $table->integer('lot_number_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lot_number_id')->references('id')->on('lot_number')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'lot_number_id']);
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
        Schema::drop('site_lot_number');
        Schema::drop('user_lot_number');
        Schema::drop('lot_number');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
