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
        Schema::create('orders_payment', function (Blueprint $table) {
            $table->bigIncrements('orp_id');
            $table->bigInteger('ord_id')->unsigned();
            $table->string('orp_payment_id')->nullable();
            $table->string('orp_order_id')->nullable();
            $table->string('orp_payer_id')->nullable();
            $table->string('orp_payer_email')->nullable();
            $table->string('orp_payer_first_name')->nullable();
            $table->string('orp_payer_last_name')->nullable();
            $table->string('orp_payer_phone')->nullable();
            $table->string('orp_payment_method_id')->nullable();
            $table->string('orp_payment_type_id')->nullable();
            $table->string('orp_status')->nullable();
            $table->string('orp_status_detail')->nullable();
            $table->float('orp_transaction_amount')->nullable();
            $table->float('orp_received_amount')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('ord_id')->references('ord_id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_payment');
    }
};
