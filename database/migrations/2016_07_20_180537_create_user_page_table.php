<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_page', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->integer('user_id')->unsigned();
            $table->integer('page_id')->unsigned();
            
            $table->foreign('user_id')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('page')->onUpdate('cascade')->onDelete('cascade');
            
            $table->primary(['user_id', 'page_id']);
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
        Schema::drop('user_page');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
