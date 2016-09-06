<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupRequestAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_request_address', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('site_id')->unsigned()->nullable();
            $table->string('name')->nullable()->default(null);
            $table->string('company_name')->nullable()->default(null);
            $table->string('company_division')->nullable()->default(null);
            $table->longText('contact_name')->nullable()->default(null);
            $table->longText('contact_phone_number')->nullable()->default(null);
            $table->longText('contact_address_1')->nullable()->default(null);
            $table->longText('contact_address_2')->nullable()->default(null);
            $table->string('contact_city')->nullable()->default(null);
            $table->string('contact_state')->nullable()->default(null);
            $table->string('contact_zip')->nullable()->default(null);
            $table->string('contact_country')->nullable()->default(null);
            $table->longText('contact_cell_number')->nullable()->default(null);
            $table->longText('contact_email_address')->nullable()->default(null);

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
        Schema::drop('pickup_request_address');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
