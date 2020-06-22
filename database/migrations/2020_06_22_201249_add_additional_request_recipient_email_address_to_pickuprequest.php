<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalRequestRecipientEmailAddressToPickuprequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickup_request', function (Blueprint $table) {
            $table->string('additional_request_recipient_email_address', 75)->after('contact_email_address')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickup_request', function (Blueprint $table) {
            $table->dropColumn('additional_request_recipient_email_address');
        });
    }
}
