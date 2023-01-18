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
        Schema::create('mercadolivre_notifications', function (Blueprint $table) {
            $table->bigIncrements('men_id');
            $table->string('men_resource');
            $table->bigInteger('men_user_id')->unsigned();
            $table->string('men_topic');
            $table->bigInteger('men_application_id')->unsigned();
            $table->integer('men_attempts')->unsigned();
            $table->timestamp('men_sent')->nullable();
            $table->timestamp('men_received')->nullable();
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
        Schema::dropIfExists('mercadolivre_notifications');
    }
};
