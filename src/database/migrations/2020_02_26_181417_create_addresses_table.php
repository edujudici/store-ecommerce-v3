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
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('adr_id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('adr_name')->nullable();
            $table->string('adr_surname')->nullable();
            $table->string('adr_phone')->nullable();
            $table->string('adr_zipcode')->nullable();
            $table->string('adr_address')->nullable();
            $table->integer('adr_number')->nullable();
            $table->string('adr_district')->nullable();
            $table->string('adr_city')->nullable();
            $table->string('adr_complement')->nullable();
            $table->enum('adr_type', ['billing', 'shipping'])->default('billing');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
