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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('pro_id');
            $table->bigInteger('cat_id')->unsigned()->nullable();
            $table->string('pro_image');
            $table->float('pro_price');
            $table->float('pro_oldprice');
            $table->string('pro_description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cat_id')->references('cat_id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
