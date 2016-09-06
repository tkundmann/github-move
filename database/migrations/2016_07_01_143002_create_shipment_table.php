<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->date('lot_date')->nullable()->default(null);
            $table->date('lot_approved_date')->nullable()->default(null);
            $table->string('lot_number', 25)->nullable()->default(null);
            $table->string('po_number', 25)->nullable()->default(null);
            $table->string('vendor_shipment_number', 30)->nullable()->default(null);
            $table->string('cost_center', 35)->nullable()->default(null);
            $table->string('site_coordinator', 50)->nullable()->default(null);
            $table->string('vendor', 40)->nullable()->default(null);
            $table->string('vendor_client', 30)->nullable()->default(null);
            $table->string('bill_of_lading', 40)->nullable()->default(null);
            $table->string('city_of_origin', 35)->nullable()->default(null);
            $table->date('schedule_pickup_date')->nullable()->default(null);
            $table->string('freight_carrier', 25)->nullable()->default(null);
            $table->string('freight_invoice_number', 50)->nullable()->default(null);
            $table->decimal('freight_charge', 10, 2)->nullable()->default(null);
            $table->date('pickup_request_date')->nullable()->default(null);
            $table->string('pickup_address', 50)->nullable()->default(null);
            $table->string('pickup_address_2', 50)->nullable()->default(null);
            $table->string('pickup_city', 50)->nullable()->default(null);
            $table->string('pickup_state', 25)->nullable()->default(null);
            $table->string('pickup_zip_code', 10)->nullable()->default(null);
            $table->date('actual_pickup_date')->nullable()->default(null);
            $table->date('date_received')->nullable()->default(null);
            $table->date('nf_received_date')->nullable()->default(null);
            $table->string('nota_fiscal_transfer', 20)->nullable()->default(null);
            $table->string('nota_fiscal_transfer_2', 20)->nullable()->default(null);
            $table->string('nota_fiscal_transfer_3', 20)->nullable()->default(null);
            $table->string('nota_fiscal_transfer_4', 20)->nullable()->default(null);
            $table->string('nota_fiscal_transfer_5', 20)->nullable()->default(null);
            $table->string('equipment_summary', 255)->nullable()->default(null);
            $table->decimal('total_weight_received', 10, 2)->nullable()->default(null);
            $table->integer('number_of_skids')->nullable()->default(null);
            $table->integer('number_of_pieces')->nullable()->default(null);
            $table->date('pre_audit_approved')->nullable()->default(null);
            $table->date('audit_completed')->nullable()->default(null);
            $table->string('cert_of_data_wipe_num', 25)->nullable()->default(null);
            $table->string('cert_of_destruction_num', 25)->nullable()->default(null);
            $table->dateTime('import_date_time')->nullable();
            $table->dateTime('update_date_time')->nullable();

            $table->index('lot_number');
            $table->index('city_of_origin');
            $table->index('site_coordinator');
            $table->index('freight_carrier');
            $table->index('po_number');
            $table->index('vendor_client');
            $table->index('lot_date');
            $table->index('bill_of_lading');
            $table->index('vendor_shipment_number');
            $table->index('freight_charge');
            $table->index('cost_center');
            $table->index('date_received');
            $table->index('audit_completed');
            $table->index('lot_approved_date');
            $table->index('equipment_summary');
            $table->index('total_weight_received');
            $table->index('cert_of_data_wipe_num');
            $table->index('cert_of_destruction_num');
            $table->index('import_date_time');
            $table->index('update_date_time');
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
        Schema::drop('shipment');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
