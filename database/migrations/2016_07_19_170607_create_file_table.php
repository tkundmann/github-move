<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('filename')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
            $table->integer('size')->nullable()->default(null);
            $table->integer('page_id')->unsigned()->nullable();
            $table->integer('shipment_id')->unsigned()->nullable();
            $table->integer('pickup_request_id')->unsigned()->nullable();
            $table->date('file_date')->nullable();
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('page')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('shipment_id')->references('id')->on('shipment')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pickup_request_id')->references('id')->on('pickup_request')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('file');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
