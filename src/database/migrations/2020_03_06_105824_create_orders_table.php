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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('ord_id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('ord_preference_id')->nullable();
            $table->integer('ord_collection_id')->unsigned()->nullable();
            $table->string('ord_collection_status')->nullable();
            $table->string('ord_external_reference')->nullable();
            $table->string('ord_payment_type')->nullable();
            $table->integer('ord_merchant_order_id')->unsigned()->nullable();
            $table->string('ord_processing_mode')->nullable();
            $table->string('ord_merchant_account_id')->nullable();
            $table->float('ord_subtotal')->nullable();
            $table->float('ord_freight')->nullable();
            $table->float('ord_total')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
