<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_request', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('site_id')->unsigned()->nullable();
            $table->string('company_name')->nullable()->default(null);
            $table->string('company_division')->nullable()->default(null);
            $table->string('contact_name')->nullable()->default(null);
            $table->string('contact_phone_number')->nullable()->default(null);
            $table->string('contact_address_1')->nullable()->default(null);
            $table->string('contact_address_2')->nullable()->default(null);
            $table->string('contact_city')->nullable()->default(null);
            $table->string('contact_state')->nullable()->default(null);
            $table->string('contact_zip')->nullable()->default(null);
            $table->string('contact_country')->nullable()->default(null);
            $table->string('contact_cell_number')->nullable()->default(null);
            $table->string('contact_email_address')->nullable()->default(null);
            $table->string('reference_number')->nullable()->default(null);
            $table->integer('num_internal_hard_drives')->nullable()->default(null);
            $table->integer('num_desktops')->nullable()->default(null);
            $table->integer('num_laptops')->nullable()->default(null);
            $table->integer('num_monitors')->nullable()->default(null);
            $table->integer('num_printers')->nullable()->default(null);
            $table->integer('num_servers')->nullable()->default(null);
            $table->integer('num_networking')->nullable()->default(null);
            $table->integer('num_storage_systems')->nullable()->default(null);
            $table->integer('num_ups')->nullable()->default(null);
            $table->integer('num_racks')->nullable()->default(null);
            $table->integer('num_other')->nullable()->default(null);
            $table->integer('num_misc')->nullable()->default(null);
            $table->integer('total_num_assets')->nullable()->default(null);
            $table->boolean('internal_hard_drive_encrypted')->nullable()->default(null);
            $table->boolean('internal_hard_drive_wiped')->nullable()->default(null);
            $table->boolean('desktop_encrypted')->nullable()->default(null);
            $table->boolean('desktop_hard_drive_wiped')->nullable()->default(null);
            $table->boolean('laptop_encrypted')->nullable()->default(null);
            $table->boolean('laptop_hard_drive_wiped')->nullable()->default(null);
            $table->boolean('server_encrypted')->nullable()->default(null);
            $table->boolean('server_hard_drive_wiped')->nullable()->default(null);
            $table->dateTime('preferred_pickup_date')->nullable()->default(null);
            $table->string('preferred_pickup_date_information')->nullable()->default(null);
            $table->boolean('units_located_near_dock')->nullable()->default(null);
            $table->boolean('units_on_single_floor')->nullable()->default(null);
            $table->boolean('is_lift_gate_needed')->nullable()->default(null);
            $table->boolean('is_loading_dock_present')->nullable()->default(null);
            $table->boolean('dock_appointment_required')->nullable()->default(null);
            $table->boolean('assets_need_packaging')->nullable()->default(null);
            $table->boolean('hardware_on_skids')->nullable()->default(null);
            $table->integer('num_skids')->nullable()->default(null);
            $table->string('bm_company_name')->nullable()->default(null);
            $table->string('bm_contact_name')->nullable()->default(null);
            $table->string('bm_phone_number')->nullable()->default(null);
            $table->string('bm_address_1')->nullable()->default(null);
            $table->string('bm_address_2')->nullable()->default(null);
            $table->string('bm_city')->nullable()->default(null);
            $table->string('bm_state')->nullable()->default(null);
            $table->string('bm_zip')->nullable()->default(null);
            $table->string('bm_country')->nullable()->default(null);
            $table->string('bm_cell_number')->nullable()->default(null);
            $table->string('bm_email_address')->nullable()->default(null);
            $table->text('special_instructions')->nullable()->default(null);
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
        Schema::drop('pickup_request');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
