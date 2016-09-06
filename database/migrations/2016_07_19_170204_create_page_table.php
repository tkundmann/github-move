<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->enum('type', ['Standard', 'Certificates of Data Wipe','Certificates of Recycling', 'Settlements'])->default('Standard');
            $table->string('name')->nullable()->default(null);
            $table->string('code')->nullable()->default(null);
            $table->text('text')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->integer('site_id')->unsigned()->nullable();
            $table->boolean('user_restricted')->nullable()->default(null);
            $table->boolean('lot_number_restricted')->nullable()->default(null);
            $table->timestamps();

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
        Schema::drop('page');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
