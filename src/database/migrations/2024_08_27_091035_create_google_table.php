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
        Schema::create('google', function (Blueprint $table) {
            $table->bigIncrements('goo_id');
            $table->string('goo_token_type')->nullable();
            $table->integer('goo_expires_in')->nullable();
            $table->text('goo_access_token')->nullable();
            $table->text('goo_refresh_token')->nullable();
            $table->string('goo_created')->nullable();
            $table->text('goo_scope')->nullable();
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
        Schema::dropIfExists('google');
    }
};
