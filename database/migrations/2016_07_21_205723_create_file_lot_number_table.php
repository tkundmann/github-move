<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLotNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_lot_number', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('file_id')->unsigned();
            $table->integer('lot_number_id')->unsigned();

            $table->foreign('file_id')->references('id')->on('file')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lot_number_id')->references('id')->on('lot_number')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['file_id', 'lot_number_id']);
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
        Schema::drop('file_lot_number');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
