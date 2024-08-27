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
        Schema::create('merchantcenter', function (Blueprint $table) {
            $table->bigIncrements('mec_id');
            $table->string('mec_token_type')->nullable();
            $table->integer('mec_expires_in')->nullable();
            $table->text('mec_access_token')->nullable();
            $table->text('mec_refresh_token')->nullable();
            $table->text('mec_authorize_code')->nullable();
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
        Schema::dropIfExists('merchantcenter');
    }
};
