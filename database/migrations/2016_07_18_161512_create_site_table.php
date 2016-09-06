<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('code')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('logo_url')->nullable()->default(null);
            $table->string('color', 10)->nullable()->default(null);
            $table->enum('type', ['Insight','SAR', 'Other'])->default('Other');
            $table->timestamps();
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
        Schema::drop('site');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
