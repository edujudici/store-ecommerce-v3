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
        Schema::create('melhorenvio', function (Blueprint $table) {
            $table->bigIncrements('mee_id');
            $table->string('mee_token_type')->nullable();
            $table->integer('mee_expires_in')->nullable();
            $table->text('mee_access_token')->nullable();
            $table->text('mee_refresh_token')->nullable();
            $table->text('mee_authorize_code')->nullable();
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
        Schema::dropIfExists('melhorenvio');
    }
};
