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
        Schema::create('orders_history', function (Blueprint $table) {
            $table->bigIncrements('orh_id');
            $table->bigInteger('ord_id')->unsigned();
            $table->string('orh_preference_id')->nullable();
            $table->string('orh_collection_status')->nullable();
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
        Schema::dropIfExists('orders_history');
    }
};
