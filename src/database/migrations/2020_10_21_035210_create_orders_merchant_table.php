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
        Schema::create('orders_merchant', function (Blueprint $table) {
            $table->bigIncrements('orm_id');
            $table->bigInteger('ord_id')->unsigned();
            $table->string('orm_notification_id');
            $table->string('orm_notification_topic');
            $table->string('orm_order_status');
            $table->float('orm_paid_amount');
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
        Schema::dropIfExists('orders_merchant');
    }
};
