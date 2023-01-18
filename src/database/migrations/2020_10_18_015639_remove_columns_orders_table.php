<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_status_uri');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_collection_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_collection_status');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_external_reference');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_payment_type');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_merchant_order_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_processing_mode');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ord_merchant_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
