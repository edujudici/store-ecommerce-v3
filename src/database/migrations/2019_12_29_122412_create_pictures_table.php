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
        Schema::create('pictures', function (Blueprint $table) {
            $table->bigIncrements('pic_id');
            $table->string('pro_sku')->nullable();
            $table->string('pic_id_secondary')->nullable();
            $table->text('pic_url')->nullable();
            $table->text('pic_secure_url')->nullable();
            $table->string('pic_size')->nullable();
            $table->string('pic_max_size')->nullable();
            $table->string('pic_quality')->nullable();
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
        Schema::dropIfExists('pictures');
    }
};
