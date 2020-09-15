<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_number', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('entry_number')->unique();
            $table->string('lot_number', 25)->nullable()->default(null);
            $table->string('package_tracking_number', 35)->nullable()->default(null);
            $table->string('package_tracking_type', 25)->nullable()->default(null);
            $table->string('shipping_agent_code', 10)->nullable()->default(null);
            $table->string('scanned', 10)->nullable()->default(null);
            $table->date('scanned_date')->nullable()->default(null);
            $table->string('scanned_time', 25)->nullable()->default(null);
            $table->string('tracking_number_url', 100)->nullable()->default(null);
            $table->dateTime('import_date_time')->nullable();
            $table->dateTime('update_date_time')->nullable();

            $table->index('lot_number');
            $table->index('package_tracking_number');
            $table->index('scanned_date');
            $table->index('import_date_time');
            $table->index('update_date_time');
        });

        Schema::create('tracking_number_archive', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('entry_number')->unique();
            $table->string('lot_number', 25)->nullable()->default(null);
            $table->string('package_tracking_number', 35)->nullable()->default(null);
            $table->string('package_tracking_type', 25)->nullable()->default(null);
            $table->string('shipping_agent_code', 10)->nullable()->default(null);
            $table->string('scanned', 10)->nullable()->default(null);
            $table->date('scanned_date')->nullable()->default(null);
            $table->string('scanned_time', 25)->nullable()->default(null);
            $table->string('tracking_number_url', 100)->nullable()->default(null);
            $table->dateTime('import_date_time')->nullable();
            $table->dateTime('update_date_time')->nullable();

            $table->index('lot_number');
            $table->index('package_tracking_number');
            $table->index('scanned_date');
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
        Schema::drop('tracking_number');
        Schema::drop('tracking_number_archive');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
