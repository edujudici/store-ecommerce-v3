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
        Schema::create('orders_address', function (Blueprint $table) {
            $table->bigIncrements('ora_id');
            $table->bigInteger('ord_id')->unsigned();
            $table->string('ora_name')->nullable();
            $table->string('ora_surname')->nullable();
            $table->string('ora_phone')->nullable();
            $table->string('ora_zipcode')->nullable();
            $table->string('ora_address')->nullable();
            $table->integer('ora_number')->nullable();
            $table->string('ora_district')->nullable();
            $table->string('ora_city')->nullable();
            $table->string('ora_complement')->nullable();
            $table->enum('ora_type', ['billing', 'shipping'])->default('billing');
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
        Schema::dropIfExists('orders_address');
    }
};
