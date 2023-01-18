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
        Schema::create('company', function (Blueprint $table) {
            $table->bigIncrements('com_id');
            $table->string('com_title')->nullable();
            $table->string('com_description')->nullable();
            $table->string('com_image')->nullable();
            $table->string('com_address')->nullable();
            $table->string('com_phone')->nullable();
            $table->string('com_work_hours')->nullable();
            $table->string('com_mail')->nullable();
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
        Schema::dropIfExists('company');
    }
};
