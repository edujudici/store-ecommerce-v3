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
        Schema::create('mercadolivre_users', function (Blueprint $table) {
            $table->bigIncrements('meu_id');
            $table->bigInteger('meu_user_id')->unsigned()->nullable();
            $table->string('meu_nickname')->nullable();
            $table->timestamp('meu_registration_date')->nullable();
            $table->string('meu_address_city')->nullable();
            $table->string('meu_address_state')->nullable();
            $table->float('meu_points')->nullable();
            $table->string('meu_permalink')->nullable();
            $table->string('meu_level_id')->nullable();
            $table->string('meu_power_seller_status')->nullable();
            $table->integer('meu_transactions_canceled')->nullable();
            $table->integer('meu_transactions_completed')->nullable();
            $table->integer('meu_transactions_total')->nullable();
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
        Schema::dropIfExists('mercadolivre_users');
    }
};
