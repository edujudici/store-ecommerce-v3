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
        Schema::create('mercadolivre_products', function (Blueprint $table) {
            $table->bigIncrements('mep_id');
            $table->string('mep_item_id');
            $table->string('mep_title');
            $table->float('mep_price');
            $table->string('mep_permalink');
            $table->string('mep_secure_thumbnail');
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
        Schema::dropIfExists('mercadolivre_products');
    }
};
