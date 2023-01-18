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
        Schema::create('orders_item', function (Blueprint $table) {
            $table->bigIncrements('ori_id');
            $table->bigInteger('ord_id')->unsigned();
            $table->bigInteger('pro_id')->unsigned();
            $table->string('pro_sku')->nullable();
            $table->integer('ori_amount')->nullable();
            $table->float('ori_price')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('orders_item');
    }
};
