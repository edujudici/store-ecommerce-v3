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
        Schema::create('orders_comment', function (Blueprint $table) {
            $table->bigIncrements('orc_id');
            $table->string('ord_id')->nullable();
            $table->string('orc_name');
            $table->text('orc_question')->nullable();
            $table->text('orc_answer')->nullable();
            $table->timestamp('orc_answer_date')->nullable();
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
        Schema::dropIfExists('orders_comment');
    }
};
