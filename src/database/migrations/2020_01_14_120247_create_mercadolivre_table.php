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
        Schema::create('mercadolivre', function (Blueprint $table) {
            $table->bigIncrements('mel_id');
            $table->string('mel_url')->nullable();
            $table->string('mel_search_type')->nullable();
            $table->string('mel_seller_id')->nullable();
            $table->string('mel_token')->nullable();
            $table->string('mel_refresh_token')->nullable();
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
        Schema::dropIfExists('mercadolivre');
    }
};
