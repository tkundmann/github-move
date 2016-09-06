<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->longText('data')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('site_feature', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('site_id')->unsigned();
            $table->integer('feature_id')->unsigned();
            $table->longText('data')->nullable();


            $table->foreign('site_id')->references('id')->on('site')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('feature_id')->references('id')->on('feature')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['site_id', 'feature_id']);
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
        Schema::drop('site_feature');
        Schema::drop('feature');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
