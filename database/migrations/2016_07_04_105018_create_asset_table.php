<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->date('lot_date')->nullable()->default(null);
            $table->string('lot_number', 25)->nullable()->default(null);
            $table->string('bill_of_lading', 100)->nullable()->default(null);
            $table->string('carrier', 50)->nullable()->default(null);
            $table->string('po_number', 25)->nullable()->default(null);
            $table->string('vendor_order_number', 50)->nullable()->default(null);
            $table->string('vendor', 40)->nullable()->default(null);
            $table->string('vendor_client', 30)->nullable()->default(null);
            $table->date('date_arrived')->nullable()->default(null);
            $table->date('shipment_date')->nullable()->default(null);
            $table->string('barcode_number', 50)->nullable()->default(null);
            $table->string('product_family', 50)->nullable()->default(null);
            $table->string('manufacturer', 50)->nullable()->default(null);
            $table->string('manufacturer_model_num', 50)->nullable()->default(null);
            $table->string('manufacturer_part_num', 50)->nullable()->default(null);
            $table->string('manufacturer_serial_num', 50)->nullable()->default(null);
            $table->string('parent_serial_num', 50)->nullable()->default(null);
            $table->string('item_number', 50)->nullable()->default(null);
            $table->string('form_factor', 30)->nullable()->default(null);
            $table->string('speed', 25)->nullable()->default(null);
            $table->string('memory', 25)->nullable()->default(null);
            $table->string('storage_capacity', 25)->nullable()->default(null);
            $table->string('dual', 25)->nullable()->default(null);
            $table->string('quad', 25)->nullable()->default(null);
            $table->string('optical_1', 25)->nullable()->default(null);
            $table->string('optical_2', 25)->nullable()->default(null);
            $table->string('nic', 25)->nullable()->default(null);
            $table->string('video', 10)->nullable()->default(null);
            $table->string('color', 25)->nullable()->default(null);
            $table->string('adapter', 10)->nullable()->default(null);
            $table->string('screen_size', 10)->nullable()->default(null);
            $table->string('battery', 10)->nullable()->default(null);
            $table->string('wifi', 10)->nullable()->default(null);
            $table->string('docking_station', 10)->nullable()->default(null);
            $table->string('stylus', 10)->nullable()->default(null);
            $table->string('firewire', 10)->nullable()->default(null);
            $table->string('keyboard', 10)->nullable()->default(null);
            $table->string('mouse', 10)->nullable()->default(null);
            $table->string('cartridge', 10)->nullable()->default(null);
            $table->string('coa', 25)->nullable()->default(null);
            $table->string('osx_description', 255)->nullable()->default(null);
            $table->string('condition', 25)->nullable()->default(null);
            $table->integer('date_code')->nullable()->default(null);
            $table->string('comments', 255)->nullable()->default(null);
            $table->text('additional_comments')->nullable()->default(null);
            $table->string('hard_drive_serial_num', 400)->nullable()->default(null);
            $table->string('asset_tag', 50)->nullable()->default(null);
            $table->string('status', 50)->nullable()->default(null);
            $table->decimal('settlement_amount', 10, 2)->nullable()->default(null);
            $table->decimal('net_settlement', 10, 2)->nullable()->default(null);
            $table->string('cert_of_data_wipe_num', 25)->nullable()->default(null);
            $table->string('cert_of_destruction_num', 25)->nullable()->default(null);
            $table->dateTime('import_date_time')->nullable();
            $table->dateTime('update_date_time')->nullable();

            $table->index('barcode_number');
            $table->index('manufacturer');
            $table->index('status');
            $table->index('vendor_client');
            $table->index('lot_date');
            $table->index('lot_number');
            $table->index('bill_of_lading');
            $table->index('shipment_date');
            $table->index('manufacturer_model_num');
            $table->index('manufacturer_part_num');
            $table->index('manufacturer_serial_num');
            $table->index('product_family');
            $table->index('condition');
            $table->index('date_code');
            $table->index('comments');
            $table->index('hard_drive_serial_num');
            $table->index('asset_tag');
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
        Schema::drop('asset');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
